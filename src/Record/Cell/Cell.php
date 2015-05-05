<?php
namespace TSwiackiewicz\ExcelStreamWriter\Record\Cell;

use TSwiackiewicz\ExcelStreamWriter\Record\Record;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException;

/**
 * Interface rekordow komorek wstawianych do arkusza
 * Wszystkie klasy komorek musza zaimplementowac ten interface
 */
abstract class Cell extends Record
{
    
    /**
     * Max liczba wierszy w arkuszu (ograniczenie formatu xls)
     * 
     * @var integer
     */
    const MAX_ROWS_COUNT = 65535;
    
    /**
     * Max liczba kolumn w arkuszu (ograniczenie formatu xls)
     * 
     * @var integer
     */
    const MAX_COLS_COUNT = 255;
    
    /**
     * Indeks rekordu XF
     * 
     * @var unsigned short
     */
    const XF_INDEX = 0x000F;

    /**
     * Numer wiersza komorki
     * 
     * @var integer
     */
    protected $row;

    /**
     * Numer kolumny komorki
     * 
     * @var integer
     */
    protected $col;

    /**
     * Inicjalizacja rekordu zawierajcaego komorke wstawiana do arkusza
     * 
     * @param PackFormatter $formatter formatter metody pack() dla rozpoznaego trybu endian
     * @param integer $row numer wiersza komorki
     * @param integer $col numer kolumny komorki
     */
    public function __construct(PackFormatter $formatter, $row, $col)
    {
        parent::__construct($formatter);
        $this->row = $row;
        $this->col = $col;
    }

    /**
     * Pobranie danych rekordu zawierajacego komorke
     * 
     * @throws InvalidRecordDataException nieprawidlowe dane rekordu komorki
     * @return string dane rekordu
     */
    protected function getRecordData()
    {
        if (!$this->isRowValid($this->row)) {
            throw new InvalidRecordDataException('Invalid row number!');
        }
        if (!$this->isColValid($this->col)) {
            throw new InvalidRecordDataException('Invalid col number!');
        }
        
        return pack($this->formatter->getFormat([
            PackFormatter::SHORT,
            PackFormatter::SHORT,
            PackFormatter::SHORT
        ]), $this->row, $this->col, self::XF_INDEX) . $this->getValue();
    }

    /**
     * Sprawdzanie poprawnosci numeru wiersza komorki
     * 
     * @param int $row numer wiersza komorki
     * @return boolean czy numer wiersza komorki jest poprawny
     */
    private function isRowValid($row)
    {
        return (!is_int($row) or $row < 0 or $row > self::MAX_ROWS_COUNT) ? false : true;
    }

    /**
     * Sprawdzanie poprawnosci numeru kolumny komorki
     * 
     * @param int $col numer kolumny komorki
     * @return boolean czy numer kolumny komorki jest poprawny
     */
    private function isColValid($col)
    {
        return (!is_int($col) or $col < 0 or $col > self::MAX_COLS_COUNT) ? false : true;
    }

    /**
     * Pobranie wartosci wstawianej do komorki arkusza
     * 
     * @throws InvalidRecordDataValueException nieprawidlowa wartosc wstawiana do komorki
     * @return string wartosc wstawiana do komorki arkusza
     */
    abstract protected function getValue();
}
