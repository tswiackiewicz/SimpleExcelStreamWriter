<?php
namespace TSwiackiewicz\SimpleExcelStreamWriter;

class SimpleExcelStreamWriter
{

    /**
     * Sciezka do pliku wynikowego
     * 
     * @var string
     */
    private $path;

    /**
     * Wykryty tryb endian
     * 
     * @var string
     */
    private $endianMode = EndianModeDetector::MACHINE_BYTE_ORDER;

    public function __construct($path, EndianModeDetector $endian)
    {
        $this->path = $path;
        $this->endianMode = $endian->detect();
    }

    public function open()
    {}

    public function close()
    {}

    public function __destruct()
    {
        $this->close();
    }
}
