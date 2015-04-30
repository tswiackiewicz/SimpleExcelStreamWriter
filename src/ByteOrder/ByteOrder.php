<?php
namespace TSwiackiewicz\SimpleExcelStreamWriter\ByteOrder;

/**
 * Klasa do wykrywania ustawionego trybu endian srodowiska
 */
class ByteOrder
{

    /**
     * Tryb Little Endian
     * 
     * @var string
     */
    const LITTLE_ENDIAN = 'little-endian';

    /**
     * Tryb Big Endian
     * 
     * @var string
     */
    const BIG_ENDIAN = 'big-endian';

    /**
     * Tryb Machine Byte Order
     * 
     * @var string
     */
    const MACHINE_BYTE_ORDER = 'machine-byte-order';

    /**
     * Wartosc uzyta do wykrywania trybu endian
     * ('abyz' w postaci szesnastkowej)
     * 
     * @var integer
     */
    protected $testValue = 0x6162797A;

    /**
     * Wykrywanie trybu endian (Little-Endian, Big-Endian, Machine Byte Order)
     * 
     * @return string tryb endian
     */
    public function getEndian()
    {
        // domyslnie Machine Byte Order
        $endianMode = self::MACHINE_BYTE_ORDER;
        
        // konwersja $abyz do 32 bitowej postaci binarnej
        // L - unsigned long (32 bit, machine byte order)
        switch ($this->getMachineByteOrderValue()) {
            case $this->getLittleEndianValue():
                $endianMode = self::LITTLE_ENDIAN;
                break;
            
            case $this->getBigEndianValue():
                $endianMode = self::BIG_ENDIAN;
                break;
        }
        
        return $endianMode;
    }

    /**
     * Pobranie testowej wartosci w formacie machine byte order
     * 
     * @return integer binarna postac testowej wartosci w formacie machine byte order
     */
    protected function getMachineByteOrderValue()
    {
        return pack('L', $this->testValue);
    }

    /**
     * Pobranie testowej wartosci w formacie little endian
     * 
     * @return integer binarna postac testowej wartosci w formacie little endian
     */
    protected function getLittleEndianValue()
    {
        return pack('V', $this->testValue);
    }

    /**
     * Pobranie testowej wartosci w formacie big endian
     * 
     * @return integer binarna postac testowej wartosci w formacie big endian
     */
    protected function getBigEndianValue()
    {
        return pack('N', $this->testValue);
    }
}
