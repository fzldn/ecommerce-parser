<?php

namespace App\Console\Commands;

use App\Brand;
use App\Category;
use App\Customer;
use App\Mail\InputParsed;
use App\Order;
use App\OrderDiscount;
use App\OrderItem;
use App\Product;
use App\ShippingAddress;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Parser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:run
        {url : url of the remote jsonl file}
        {--f|format=csv : output format csv or jsonl}
        {--e|email= : send output file to your email}
        {--i|db : import to database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse ecommerce order data from remote jsonlines file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = $this->argument('url');
        $format = $this->option('format');
        $email = $this->option('email');
        $db = $this->option('db');
        $validFormats = ['csv', 'jsonl'];
        if (!in_array($format, $validFormats)) {
            $this->error(sprintf("Invalid format %s, only valid format is %s", $format, implode(',', $validFormats)));
            return;
        }
        $chunkSize = 1024 * 1024; // 1Mb
        if ($downloadedFilePath = $this->downloadChunked($url, $chunkSize)) {
            $outputFilePath = $this->process($downloadedFilePath, $format, $db);
            if ($email) {
                $this->line(sprintf("Sending output data to %s", $email));
                Mail::to($email)->send(new InputParsed($outputFilePath));
                $this->info("Email sent!");
            }
        };
    }

    /**
     * Download remote file over HTTP one small chunk at a time.
     *
     * @param string $url The full URL to the remote file
     * @param integer $length Chunk size in bytes
     * @return mixed the path of downloaded file, returns FALSE if error.
     */
    public function downloadChunked(string $url, int $length)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'LEODP_'); // stands for Laravel Ecommerce Order Data Parser, lol

        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            $parts['path'] .= '?' . $parts['query'];
        }

        $this->comment(sprintf("Downloading %s", $url));
        $inputHandle = fsockopen($parts['host'], 80, $errstr, $errcode, 5);
        $outputHandle = fopen($tempFile, 'wb');

        if ($inputHandle == false || $outputHandle == false) {
            return false;
        }

        /**
         * Send the request to the server for the file
         */
        $request = [
            "GET {$parts['path']} HTTP/1.1",
            "Host: {$parts['host']}",
            "User-Agent: Mozilla/5.0",
            "Keep-Alive: 115",
            "Connection: keep-alive",
            "",
            "",
        ];
        fwrite($inputHandle, implode("\r\n", $request));

        /**
         * Now read the headers from the remote server. We'll need
         * to get the content length.
         */
        $headers = [];
        while (!feof($inputHandle)) {
            $line = fgets($inputHandle);
            if ($line == "\r\n") {
                break;
            }
            $headers[] = $line;
        }

        /**
         * Look for the Content-Length header, and get the size
         * of the remote file.
         */
        $contentLength = 0;
        foreach ($headers as $header) {
            if (stripos($header, 'Content-Length:') === 0) {
                $contentLength = (int) str_replace('Content-Length: ', '', $header);
                break;
            }
        }
        $bar = $this->output->createProgressBar($contentLength);

        /**
         * Start reading in the remote file, and writing it to the
         * local file one chunk at a time.
         */
        $bar->start();
        $totalLength = 0;
        while (!feof($inputHandle)) {
            $buffer = fread($inputHandle, $length);
            $bytes = fwrite($outputHandle, $buffer);
            if ($bytes == false) {
                return false;
            }
            $totalLength += $bytes;
            $bar->advance($bytes);

            /**
             * We're done reading when we've reached the content length
             */
            if ($totalLength >= $contentLength) {
                $bar->finish();
                $this->line(" Downloaded");
                break;
            }
        }

        fclose($inputHandle);
        fclose($outputHandle);
        $this->info(sprintf("File stored in %s", $tempFile));

        return $tempFile;
    }

    /**
     * Process downloaded jsonlines file into specific output
     *
     * @param string $inputPath Downloaded jsonl file input
     * @param string $format Output file format
     * @param bool $DbImport Import to db
     * @return string
     */
    public function process(string $inputPath, string $format, bool $DbImport = false)
    {
        $handle = fopen($inputPath, 'r');
        $writerName = sprintf("\App\Libraries\%sWriter", ucfirst($format));
        $writer = new $writerName();
        $count = 0;
        while (!feof($handle)) {
            if ($buffer = fgets($handle)) {
                $collection = collect(json_decode($buffer, true));
                $this->line(sprintf("Processing Order ID: %d", $collection['order_id']));
                if ($DbImport) {
                    $this->dbImport($collection);
                }
                $order = [
                    'order_id' => $collection['order_id'],
                    'order_datetime' => Carbon::parse($collection['order_date'])
                        ->format(DateTime::ISO8601),
                    'total_order_value' => $this->getTotalOrder($collection),
                    'average_unit_price' => $this->getAvgUnitPrice($collection),
                    'distinct_unit_count' => $this->getDistinctUnitCount($collection),
                    'total_units_count' => $this->getUnitsCount($collection),
                    'customer_state' => $collection['customer']['shipping_address']['state'],
                ];
                if ($order['total_order_value'] > 0) {
                    $writer->append($order);
                    $count++;
                }
            }
        }
        $writer->close();
        fclose($handle);

        $this->line(str_repeat('-', 80));
        $this->info(sprintf("%d Order data processed!", $count));
        $this->info(sprintf("Output file data stored in %s", $writer->getFilePath()));
        return $writer->getFilePath();
    }

    /**
     * Import data to database
     *
     * @param Collection $order
     * @return void
     */
    public function dbImport(Collection $collection)
    {
        DB::transaction(function () use ($collection) {
            $order = Order::updateOrCreate(['id' => $collection['order_id']], $collection
                ->merge(['created_at' => Carbon::parse($collection['order_date'])])
                ->only(['shipping_price', 'created_at'])
                ->all());

            $customerData = collect($collection['customer']);
            $customer = Customer::updateOrCreate(['id' => $customerData['customer_id']], $customerData
                ->only([
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                ])
                ->all());
            $order->customer()->associate($customer);

            ShippingAddress::updateOrCreate(
                ['customer_id' => $customer->id],
                $customerData['shipping_address']
            );

            $order->items()->delete();
            foreach ($collection['items'] as $item) {
                $productData = collect($item['product'])->merge(['price' => $item['unit_price']]);
                $product = Product::updateOrCreate(['id' => $productData['product_id']], $productData->only([
                    'title',
                    'subtitle',
                    'image',
                    'thumbnail',
                    'url',
                    'upc',
                    'gtin14',
                    'price',
                    'created_at'
                ])->all());

                if ($productData['brand']) {
                    $brandData = collect($productData['brand']);
                    $brand = Brand::updateOrCreate($brandData->only(['id'])->all(), $brandData->only(['name'])->all());
                    $product->brand()->associate($brand);
                }

                $categoryIds = [];
                foreach ($productData['category'] as $name) {
                    $category = Category::firstOrCreate(['name' => $name]);
                    $categoryIds[] = $category->id;
                }

                $product->categories()->sync($categoryIds);
                $product->save();

                $orderItem = new OrderItem(collect($item)->only([
                    'quantity',
                    'unit_price',
                ])->all());
                $orderItem->order()->associate($order);
                $orderItem->product()->associate($product);
                $orderItem->save();
            }

            $order->discounts()->delete();
            foreach ($collection['discounts'] as $discount) {
                $orderDiscount = new OrderDiscount($discount);
                $orderDiscount->order()->associate($order);
                $orderDiscount->save();
            }

            $order->save();
        });
    }

    /**
     * Get the dollar sum of all line items in the order, ​excluding​ shipping, with all discounts applied.
     *
     * @param Collection $order
     * @return float
     */
    public function getTotalOrder(Collection $order)
    {
        $total = collect($order['items'])->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });
        collect($order['discounts'])->sortBy('priority')->each(function ($discount) use (&$total) {
            switch ($discount['type']) {
                case 'DOLLAR':
                    $total -= $discount['value'];
                    break;
                case 'PERCENTAGE':
                    $total -= $total * $discount['value'] / 100;
                    break;
            }
        });
        return round($total, 2);
    }

    /**
     * Get the average price of each unit in the order, in dollars.
     *
     * @param Collection $order
     * @return float
     */
    public function getAvgUnitPrice(Collection $order)
    {
        $avg = collect($order['items'])->avg('unit_price');
        return round($avg, 2);
    }

    /**
     * Get the count of unique units contained in the order.
     *
     * @param Collection $order
     * @return int
     */
    public function getUnitsCount(Collection $order)
    {
        return collect($order['items'])->count();
    }

    /**
     * Get the count of unique units contained in the order.
     *
     * @param Collection $order
     * @return int
     */
    public function getDistinctUnitCount(Collection $order)
    {
        return collect($order['items'])
            ->countBy(function ($item) {
                return $item['product']['product_id'];
            })
            ->count();
    }
}
