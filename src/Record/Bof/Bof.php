<?php
namespace TSwiackiewicz\ExcelStreamWriter\Record\Bof;

use TSwiackiewicz\ExcelStreamWriter\Record\Record;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;

/**
 * Rekord ze znacznikiem poczatku pliku
 * Struktura rekordu BOF:
 *
 * Offset    Field_name    Size    Contents
 * ----------------------------------------------------------------
 * 0         vers          1       version:
 *                                 = 00 BIFF2
 *                                 = 02 BIFF3
 *                                 = 04 BIFF4
 *                                 = 08 BIFF5/BIFF7/BIFF8
 * 1         bof           1       09h
 * 4         vers          2       version number:
 *                                 = 0500h BIFF5
 *                                 = 0600h BIFF8
 * 6         dt            2       substream type:
 *                                 = 0005h workbook globals
 *                                 = 0006h Visual Basic mode
 *                                 = 0010h worksheet or dialog sheet
 *                                 = 0020h chart
 *                                 = 0040h Excel 4.0 macro sheet
 *                                 = 0100h workspace file
 * 8         rupBuild      2       build identifier (= 0DBBh Excel 97)
 * 10        rupYear       2       build year (= 07CCh Excel 97)
 * 12        bfh           4       file history flags
 * 16        sfo           4       lowest BIFF version
 */
class Bof extends Record
{

    /**
     * Numer wersji dla BIFF5
     * 
     * @var unsigned short
     */
    const VERS_BIFF5 = 0x0500;

    /**
     * Numer wersji dla BIFF8
     * 
     * @var unsigned short
     */
    const VERS_BIFF8 = 0x0600;

    /**
     * Typ strumienia: workbook globals
     * 
     * @var unsigned short
     */
    const DT_WORKBOOK = 0x0005;

    /**
     * Typ strumienia: Visual Basic mode
     * 
     * @var unsigned short
     */
    const DT_VISUAL_BASIC_MODE = 0x0006;

    /**
     * Typ strumienia: worksheet or dialog sheet - pojedynczy arkusz
     * 
     * @var unsigned short
     */
    const DT_WORKSHEET = 0x0010;

    /**
     * Typ strumienia: chart
     * 
     * @var unsigned short
     */
    const DT_CHART = 0x0020;

    /**
     * Typ strumienia: Excel 4.0 macro sheet
     * 
     * @var unsigned short
     */
    const DT_EXCEL_40_MACRO_SHEET = 0x0040;

    /**
     * Typ strumienia: workspace file
     * 
     * @var unsigned short
     */
    const DT_WORKSPACE = 0x0100;

    /**
     * Pobranie numeru rekordu ze znacznikiem poczatku pliku
     * 
     * @return string numer rekordu
     */
    protected function getRecordNumber()
    {
        return 0x0809;
    }

    /**
     * Pobranie danych rekordu ze znacznikiem poczatku pliku
     * 
     * @return string dane rekordu ze znacznikiem poczatku pliku
     */
    protected function getRecordData()
    {
        // numer wersji (BIFF8 - obsluga Unicode)
        $version = self::VERS_BIFF8;
        // typ (worksheet - pojedynczy arkusz)
        $type = self::DT_WORKSHEET;
        // identyfikator builda (Excel 97)
        $rupBuild = 0x0DBB;
        // build year (Excel 97)
        $rupYear = 0x07CC;
        
        // wartosci bfh oraz sfo "podejrzane" na rzeczywistych plikach MS Excel 2007
        $bfh = 0x000100D1;
        $sfo = 0x00000406;
        
        return pack($this->formatter->getFormat([
            PackFormatter::SHORT,
            PackFormatter::SHORT,
            PackFormatter::SHORT,
            PackFormatter::SHORT,
            PackFormatter::LONG,
            PackFormatter::LONG
        ]), $version, $type, $rupBuild, $rupYear, $bfh, $sfo);
    }
}
