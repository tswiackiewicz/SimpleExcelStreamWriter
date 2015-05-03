<?php
namespace TSwiackiewicz\ExcelStreamWriter\Record\Cell;

use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataValueException;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

/**
 * Rekord zawierajacy komorke z wartoscia liczbowa
 * Struktura danych (offset 0-3 to naglowek) dla komorki z wartoscia liczbowa (NUMBER):
 *
 * Offset    Name    Size    Contents
 * ----------------------------------------------
 * 4         rw      2       row
 * 6         col     2       column
 * 8         ixfe    2       index to the XF record
 * 10        num     8       floating-point value
 */
class NumberCell extends Cell
{

    /**
     * Wartosc wstawiana do komorki
     * 
     * @var numeric
     */
    private $value;

    /**
     * Inicjalizacja komorki
     * 
     * @param PackFormatter $formatter formatter metody pack() dla rozpoznaego trybu endian
     * @param int $row numer wiersza komorki
     * @param int $col numer kolumny komorki
     * @param numeric $value wartosc wstawiana do komorki
     */
    public function __construct(PackFormatter $formatter, $row, $col, $value)
    {
        parent::__construct($formatter, $row, $col);
        $this->value = $value;
    }

    /**
     * Pobranie numeru rekordu z komorka zawierajaca liczbe
     * 
     * @return string numer rekordu
     */
    protected function getRecordNumber()
    {
        return 0x0203;
    }

    /**
     * Pobranie wartosci wstawianej do komorki arkusza
     * 
     * @throws InvalidRecordDataValueException nieprawidlowa wartosc wstawiana do komorki
     * @return string wartosc wstawiana do komorki arkusza
     */
    protected function getValue()
    {
        if (! $this->isValueValid($this->value)) {
            throw new InvalidRecordDataValueException('Invalid value - numeric value is expected!');
        }
        
        $value = pack($this->formatter->getFormat([
            PackFormatter::DOUBLE
        ]), $this->value);
        
        // jesli Big-Endian, zamieniamy kolejnosc
        if (ByteOrder::BIG_ENDIAN == $this->formatter->getEndian()) {
            $value = strrev($value);
        }
        
        return $value;
    }

    /**
     * Czy wartosc komorki jest poprawna
     * 
     * @param numeric $value wartosc komorki
     * @return boolean czy wartosc komorki jest poprawna
     */
    private function isValueValid($value)
    {
        return is_numeric($value) ? true : false;
    }
}
