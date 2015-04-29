<?php
namespace TSwiackiewicz\SimpleExcelStreamWriter;

class SimpleExcelStreamWriter
{

    private $endianMode;

    private $path;

    public function __construct(EndianMode $endian, $path)
    {
        $this->endianMode = $endian->detect();
        $this->path = $path;
    }
}