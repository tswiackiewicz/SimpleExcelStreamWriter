<?php
namespace TSwiackiewicz\ExcelStreamWriter\Record;

use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;

/**
 * Interface rekordow wstawianych do arkusza
 * Wszystkie rekordy musza zaimplementowac ten interface
 */
abstract class Record
{

    /**
     * Formatter metody pack()
     * 
     * @var PackFormatter
     */
    protected $formatter;

    /**
     * Inicjalizacja rekordu
     * 
     * @param PackFormatter $formatter formatter metody pack() dla rozpoznaego trybu endian
     */
    public function __construct(PackFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Pobranie rekordu
     *
     * Wszystkie rekordy BIFF maja nastepujaca strukture:
     *
     * Offset    Length (bytes)    Contents
     * ----------------------------------------------------------------
     * 0         2                 record number (16-bit word identifies the record)
     * 2         2                 record data length (16-bit word equals the length
     *                             of the following record data)
     * 4         var               record data
     *
     * @throws InvalidRecordNumberException nieprawidlowy numer rekordu
     * @return string pobierany rekord
     */
    public function getRecord()
    {
        $recordNumber = $this->getRecordNumber();
        if (!$this->isRecordNumberValid($recordNumber)) {
            throw new InvalidRecordNumberException('Invalid record number!');
        }
        $recordData = $this->getRecordData();
        
        // dlugosc wstawianych danych
        $length = !empty($recordData) ? strlen($recordData) : 0x0000;
        // naglowek
        $header = pack($this->formatter->getFormat([
            PackFormatter::SHORT,
            PackFormatter::SHORT
        ]), $recordNumber, $length);
        
        // rekord BIFF sklada sie z naglowka (header, bity 0-3) oraz danych
        return $header . $recordData;
    }

    /**
     * Pobranie numeru rekordu
     * 
     * @return string numeru rekordu
     */
    abstract protected function getRecordNumber();

    /**
     * Czy podany numer rekordu jest poprawny
     * 
     * @param string $recordNumber sprawdzany numer rekordu
     * @return boolean czy podany numer rekordu jest poprawny
     */
    private function isRecordNumberValid($recordNumber)
    {
        return !empty($recordNumber) ? true : false;
    }

    /**
     * Pobranie danych rekordu
     * 
     * @throws InvalidRecordDataException nieprawidlowe dane rekordu
     * @return string dane rekordu
     */
    abstract protected function getRecordData();
}
