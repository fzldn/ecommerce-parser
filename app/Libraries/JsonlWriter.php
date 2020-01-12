<?php

namespace App\Libraries;

class JsonlWriter extends OutputWriter
{
    protected $handle;

    public function __construct(string $path = "out.jsonl")
    {
        $this->filePath = base_path($path);
        $this->handle = fopen($this->filePath, 'w');
    }

    public function append(array $order)
    {
        fwrite($this->handle, json_encode($order) . "\n");
    }

    public function close()
    {
        fclose($this->handle);
    }
}
