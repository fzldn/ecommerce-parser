<?php

namespace App\Libraries;

use League\Csv\Writer;

class CsvWriter extends OutputWriter
{
    protected $writer;

    public function __construct(string $path = "out.csv")
    {
        $this->filePath = base_path($path);
        $this->writer = Writer::createFromPath($this->filePath, 'w');
        $this->writer->setNewline("\r\n"); //RFC4180 Line feed
        $this->writer->insertOne([
            'order_id',
            'order_datetime',
            'total_order_value',
            'average_unit_price',
            'distinct_unit_count',
            'total_units_count',
            'customer_state',
        ]);
    }

    public function append(array $order)
    {
        $this->writer->insertOne($order);
    }

    public function close()
    {
        # code...
    }
}
