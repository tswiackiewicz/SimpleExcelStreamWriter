<?php
namespace TSwiackiewicz\ExcelStreamWriter\Record\Eof;

use TSwiackiewicz\ExcelStreamWriter\Record\Record;

/**
 * Rekord ze znacznikiem konca pliku
 */
class Eof extends Record
{

    /**
     * Pobranie numeru rekordu ze znacznikiem konca pliku
     * 
     * @return string numer rekordu
     */
    protected function getRecordNumber()
    {
        return 0x0A;
    }

    /**
     * Pobranie danych rekordu znacznika konca pliku
     * 
     * @return string dane rekordu
     */
    protected function getRecordData()
    {
        return '';
    }
}
