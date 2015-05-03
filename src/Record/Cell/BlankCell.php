<?php
namespace TSwiackiewicz\ExcelStreamWriter\Record\Cell;

/**
 * Rekord zawierajacy pusta komorke
 * Struktura danych (offset 0-3 to naglowek) dla pustej komorki (BLANK):
 *
 * Offset    Name    Size    Contents
 * ----------------------------------------------
 * 4         rw      2       row
 * 6         col     2       column
 * 8         ixfe    2       index to the XF record
 */
class BlankCell extends Cell
{

    /**
     * Pobranie numeru rekordu pustej komorki
     * 
     * @return string numer rekordu
     */
    protected function getRecordNumber()
    {
        return 0x0201;
    }

    /**
     * Pobranie wartosci wstawianej do komorki arkusza
     * 
     * @return string wartosc wstawiana do komorki arkusza
     */
    protected function getValue()
    {
        return '';
    }
}
