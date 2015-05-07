<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record;

use TSwiackiewicz\ExcelStreamWriter\Record\Record;

/**
 * Pusty obiekt rekordu wstawianego do arkusza
 */
class NullRecord extends Record
{

    /**
     * Pobranie rekordu
     * 
     * @throws InvalidRecordNumberException nieprawidlowy numer rekordu
     * @return string pobierany rekord
     */
    public function getRecord()
    {
        return '';
    }

    /**
     * Pobranie numeru rekordu
     * 
     * @return string numeru rekordu
     */
    protected function getRecordNumber()
    {
        return '';
    }

    /**
     * Pobranie danych rekordu
     * 
     * @throws InvalidRecordDataException nieprawidlowe dane rekordu
     * @return string dane rekordu
     */
    protected function getRecordData()
    {
        return '';
    }
}
