<?php
namespace TSwiackiewicz\ExcelStreamWriter\Record\CodePage;

use TSwiackiewicz\ExcelStreamWriter\Record\Record;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;

/**
 * Rekord definiujacy wybrana strone kodowa
 */
class CodePage extends Record
{

    /**
     * Wartosc rekordu ze strona kodowa ASCII
     * 
     * @var usigned short
     */
    const ASCII_CODE_PAGE = 0x016F;

    /**
     * Wartosc rekordu ze strona kodowa UTF-16 (BIFF8)
     * 
     * @var usigned short
     */
    const UTF16_CODE_PAGE = 0x04B0;

    /**
     * Wartosc rekordu ze strona kodowa Windows CP-1250 (Latin II)
     * 
     * @var usigned short
     */
    const CP1250_CODE_PAGE = 0x04E2;

    /**
     * Wartosc rekordu ze strona kodowa Windows CP-1251 (Cyrylic)
     * 
     * @var usigned short
     */
    const CP1251_CODE_PAGE = 0x04E3;

    /**
     * Wartosc rekordu ze strona kodowa ASNI Windows / Windows CP-1252 (Latin I)
     * 
     * @var usigned short
     */
    const CP1252_CODE_PAGE = 0x04E4;

    /**
     * Wartosc rekordu ze strona kodowa Windows CP-1253 (Greek)
     * 
     * @var usigned short
     */
    const CP1253_CODE_PAGE = 0x04E5;

    /**
     * Wartosc rekordu ze strona kodowa Windows CP-1254 (Turkish)
     * 
     * @var usigned short
     */
    const CP1254_CODE_PAGE = 0x04E6;

    /**
     * Wartosc rekordu ze strona kodowa Windows CP-1255 (Hebrew)
     * 
     * @var usigned short
     */
    const CP1255_CODE_PAGE = 0x04E7;

    /**
     * Wartosc rekordu ze strona kodowa Windows CP-1256 (Arabic)
     * 
     * @var usigned short
     */
    const CP1256_CODE_PAGE = 0x04E8;

    /**
     * Wartosc rekordu ze strona kodowa Windows CP-1257 (Baltic)
     * 
     * @var usigned short
     */
    const CP1257_CODE_PAGE = 0x04E9;

    /**
     * Wartosc rekordu ze strona kodowa Windows CP-1258 (Vietnamese)
     * 
     * @var usigned short
     */
    const CP1258_CODE_PAGE = 0x04EA;

    /**
     * Wartosc rekordu ze strona kodowa Windows CP-1361 (Korean)
     * 
     * @var usigned short
     */
    const CP1361_CODE_PAGE = 0x0551;

    /**
     * Pobranie numeru rekordu definiujacego strone kodowa
     * 
     * @return string numer rekordu
     */
    protected function getRecordNumber()
    {
        return 0x0042;
    }

    /**
     * Pobranie danych rekordu definiujacego strone kodowa
     * 
     * @return string dane rekordu
     */
    protected function getRecordData()
    {
        return pack($this->formatter->getFormat([
            PackFormatter::SHORT
        ]), self::UTF16_CODE_PAGE);
    }
}
