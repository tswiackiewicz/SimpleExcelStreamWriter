<?php
namespace TSwiackiewicz\ExcelStreamWriter\Record\Cell;

use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;

/**
 * Rekord zawierajacy komorke z wartoscia tekstowa
 * Struktura danych (offset 0-3 to naglowek) dla komorki z tekstem (LABEL):
 *
 * Offset    Name    Size    Contents
 * ----------------------------------------------
 * 4         rw      2       row
 * 6         col     2       column
 * 8         ixfe    2       index to the XF record
 * 10        cch     2       length of the string (must be <= 255)
 * 12        grbit   1       option flags
 * 13        rgb     var     array of string characters (UTF-16 encoding)
 */
class StringCell extends Cell
{

    /**
     * Max liczba znakow dla labela
     * 
     * @var int
     */
    const MAX_LABEL_LENGTH = 255;

    /**
     * Flaga grbit - compressed
     * 
     * @var unsigned short
     */
    const GRBIT_F_HIGH_BYTE_COMPRESSED = 0x0000;

    /**
     * Flaga grbit - not compressed
     * 
     * @var unsigned short
     */
    const GRBIT_F_HIGH_BYTE_NOT_COMPRESSED = 0x0001;

    /**
     * Wartosc wstawiana do komorki
     * 
     * @var string
     */
    private $value;

    /**
     * Inicjalizacja komorki
     * 
     * @param PackFormatter $formatter formatter do pakowania
     * @param int $row numer wiersza komorki
     * @param int $col numer kolumny komorki
     * @param string $value wartosc wstawiana do komorki
     */
    public function __construct(PackFormatter $formatter, $row, $col, $value)
    {
        parent::__construct($formatter, $row, $col);
        $this->value = $value;
    }

    /**
     * Pobranie numeru rekordu z komorka zawierajaca tekst
     * 
     * @return string numeru rekordu
     */
    protected function getRecordNumber()
    {
        return 0x0204;
    }

    /**
     * Pobranie wartosci wstawianej do komorki arkusza
     * Zwracany bedzie ciag nieskompresowany (no rich text, no Asian phonetics)
     *
     * Struktura danych dla ciagow znakowych Unicode:
     *
     * Offset    Field_name    Size    Contents
     * ----------------------------------------------
     * 0         cch           2       count of characters in the string 
     *                                 (the number of characters, 
     *                                 NOT the number of bytes)
     * 2         grbit         1       option flags
     * 3         rgb           var     array of string characters and 
     *                                 formatting runs
     *
     * Definicja grbit:
     *
     * Bits    Mask    Flag_name    Contentes
     * ----------------------------------------------
     * 0       01h     fHighByte    = 0 if all the characters in the string 
     *                              have a high byte of 00h and only the low 
     *                              bytes are saved in the file (compressed)
     *                              = 1 if at least one character in the string
     *                              has a nonzero high byte and therefore all
     *                              characters in the string are saved as 
     *                              double-byte characters (not compressed)
     * 1       02h     (Reserved)   Reserved; must be 0 (zero)
     * 2       04h     fExtSt       Asian phonetic settings (phonetic):
     *                              = 0 does not contain Asian phonetic settings
     *                              = 1 contains Asian phonetic settings
     * 3       08h     fRichSt      Rich-Text settings (richtext):
     *                              = 0 does not contain Rich-Text settings
     *                              = 1 contains Rich-Text settings
     * 7-4     F0h     (Reserved)   Reserved; must be 0 (zero)
     * 
     * @return string wartosc wstawiana do komorki arkusza
     */
    protected function getValue()
    {
        // liczba znakow
        $length = mb_strlen($this->value, 'UTF-8');
        // format xls ma ograniczenie do 255 znakow dla LABEL
        if ($length >= self::MAX_LABEL_LENGTH) {
            $length = self::MAX_LABEL_LENGTH;
            $this->value = mb_substr($this->value, 0, $length, 'UTF-8');
        }
        
        // zmiana kodowania na Unicode (UTF-16LE)
        $unicodeEncodedValue = mb_convert_encoding($this->value, 'UTF-16LE', 'UTF-8');
        
        return pack($this->formatter->getFormat([
            PackFormatter::SHORT,
            PackFormatter::CHAR
        ]), $length, self::GRBIT_F_HIGH_BYTE_NOT_COMPRESSED) . $unicodeEncodedValue;
    }
}
