<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InputParsed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Output file path
     *
     * @var string
     */
    protected $outputFilePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $outputFilePath)
    {
        $this->outputFilePath = $outputFilePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("e-Commerce Order Data")
            ->markdown('emails.input_parsed')
            ->attach($this->outputFilePath);
    }
}
