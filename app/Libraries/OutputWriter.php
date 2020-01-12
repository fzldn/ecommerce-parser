<?php

namespace App\Libraries;

abstract class OutputWriter
{
    protected $filePath;

    abstract public function append(array $order);

    abstract public function close();

    public function getFilePath()
    {
        return $this->filePath;
    }
}
