<?php
namespace TSwiackiewicz\SimpleExcelStreamWriter;

/**
 * Klasa do wykrywania ustawionego trybu endian srodowiska
 */
class EndianMode
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
    private $testValue = 0x6162797A;

    /**
     * @param integer $testValue
     */
    public function setTestValue($testValue)
    {
        $this->testValue = $testValue;
    }

    /**
     * Wykrywanie trybu endian (Little-Endian, Big-Endian, Machine Byte Order)
     * 
     * @return string tryb endian
     */
    public function detect()
    {
        // domyslnie Machine Byte Order
        $endianMode = self::MACHINE_BYTE_ORDER;
        
        // konwersja $abyz do 32 bitowej postaci binarnej
        // L - unsigned long (32 bit, machine byte order)
        switch ($this->packData('L', $this->testValue)) {
            case $this->packData('V', $this->testValue):
                $endianMode = self::LITTLE_ENDIAN;
                break;
            
            case $this->packData('N', $this->testValue):
                $endianMode = self::BIG_ENDIAN;
                break;
        }
        
        return $endianMode;
    }

    /**
     * Konwertowanie danych zgodnie z podanym binarnym formatem
     * 
     * @param string $format format zgodnie z ktorym dane sa pakowane
     * @param integer $data dane konwertowane do formatu binarnego
     * @return string ciag binarny zawierajacy podane dane w ustalonym formacie
     */
    private function packData($format, $data)
    {
        return pack($format, $data);
    }
}
