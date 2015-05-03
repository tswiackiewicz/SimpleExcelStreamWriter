<?php
namespace TSwiackiewicz\ExcelStreamWriter\Record;

use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Record\Bof\Bof;
use TSwiackiewicz\ExcelStreamWriter\Record\Eof\Eof;
use TSwiackiewicz\ExcelStreamWriter\Record\CodePage\CodePage;
use TSwiackiewicz\ExcelStreamWriter\Record\Cell\BlankCell;
use TSwiackiewicz\ExcelStreamWriter\Record\Cell\NumberCell;
use TSwiackiewicz\ExcelStreamWriter\Record\Cell\StringCell;

/**
 * Fabryka rekordow
 */
class RecordFactory
{

    /**
     * Formatter metody pack()
     * 
     * @var PackFormatter
     */
    private $formatter;

    /**
     * Inicjalizacja fabryki
     * 
     * @param PackFormatter $formatter formatter metody pack() dla rozpoznaego trybu endian
     */
    public function __construct(PackFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Pobranie rekordu ze znacznikiem poczatku pliku
     * 
     * @return Bof rekord ze znacznikiem poczatku pliku
     */
    public function getBof()
    {
        return new Bof($this->formatter);
    }

    /**
     * Pobranie rekordu ze znacznikiem konca pliku
     * 
     * @return Eof rekord ze znacznikiem konca pliku
     */
    public function getEof()
    {
        return new Eof($this->formatter);
    }

    /**
     * Pobranie rekordu z definicja strony kodowej
     * 
     * @return CodePage rekord z definicja strony kodowej
     */
    public function getCodePage()
    {
        return new CodePage($this->formatter);
    }

    /**
     * Pobranie rekordu z pusta komorka
     * 
     * @param int $row numer wiersza komorki
     * @param int $col numer kolumny komorki
     * @return BlankCell rekord z pusta komorka
     */
    public function getBlankCell($row, $col)
    {
        return new BlankCell($this->formatter, $row, $col);
    }

    /**
     * Pobranie rekordu z komorka zawierajaca wartosc liczbowa
     * 
     * @param int $row numer wiersza komorki
     * @param int $col numer kolumny komorki
     * @param numer $value wartosc liczbowa wstawiana do komorki
     * @return NumberCell rekord zawierajacy komorke z wartoscia liczbowa
     */
    public function getNumberCell($row, $col, $value)
    {
        return new NumberCell($this->formatter, $row, $col, $value);
    }

    /**
     * Pobranie rekordu z komorka zawierajaca tekst
     * 
     * @param int $row numer wiersza komorki
     * @param int $col numer kolumny komorki
     * @param string $value wartosc tekstowa wstawiana do komorki
     * @return StringCell rekord zawierajcy komorke z wartoscia tekstowa
     */
    public function getStringCell($row, $col, $value)
    {
        return new StringCell($this->formatter, $row, $col, $value);
    }
}
