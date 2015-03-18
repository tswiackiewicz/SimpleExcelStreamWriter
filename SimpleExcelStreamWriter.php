<?php
/**
 * Klasa umozliwiajaca strumieniowy zapis plikow w formacie .xls 
 * (BIFF - Binary Interchange File Format). 
 * 
 * Domyslnie przyjeta zostala obsluga formatu BIFF8 (MS Excel 97 / 2000,
 * MS Excel 2002 itd.) z uwagi na obslugiwane kodowanie Unicode (UTF16-LE). 
 * W celu dostosowania do obslugi innego formatu, np. BIFF5 konieczna bedzie
 * zmiana znacznika poczatku pliku (SimpleExcelStreamWriter::addBof()) oraz 
 * sposobu reprezentacji stringow (SimpleExcelStreamWriter::getStringValue()).
 * 
 * Dokument w formacie BIFF sklada sie z rekordow o ustalonej strukturze - 
 * wszelkie dane (lacznie z BOF, EOF czy ustawianiem strony kodowej) do arkusza
 * wstawiane sa poprzez dodanie odpowiedniego rekordu do dokumentu. 
 * Pliki w formacie BIFF rozpoczynaja sie znacznikiem BOF (Beginning of File) 
 * oraz koncza znacznikiem EOF (End of File). 
 * 
 * Klasa wspiera rozne architektury (Little-Endian, Big-Endian), obslugiwany 
 * jest pojedynczy arkusz (worksheet) bez obslugi naglowka continue (gdy 
 * dlugosc rekordu przekracza 8228 b), rich text, obrazkow itd.
 * 
 * Dodatkowo generowany arkusz zostal ograniczony do 65535 wierszy oraz 
 * 255 kolumn (ograniczenia formatu .xls)
 * 
 * Udostepnione zostaly nastepujace metody publiczne:
 * - addCell()            wstawia komorke o podanej zawartosci pod wskazany 
 *                        adres (row, col)
 * - addRow()             wstawia dane do wiersza o podanym numerze (row)
 * - addNextRow()         wstawia kolejny wiersz z danymi
 * - getRowCount()        pobranie numeru ostatnio wstawionego wiersza 
 *                        za pomoca ExcelStream::addNextRow()
 * 
 * Przykladowe wykorzystanie klasy:
 * 
 *     $file_path = '/tmp/sample.xls';
 *     $objExcelStream = new SimpleExcelStreamWriter($file_path);
 *     $objExcelStream->addNextRow($headers);
 *     $objExcelStream->addNextRow($row_1);
 *                 :
 *                 :
 *     $objExcelStream->addNextRow($row_n);
 *     $objExcelStream->close();
 *
 * Zapis do pliku powinno zakonczyc wywolanie SimpleExcelStream::close()
 */
final class SimpleExcelStreamWriter
{
    /**
     * Max liczba wierszy w arkuszu (ograniczenie formatu .xls)
     * @var long
     */
    const MAX_ROWS_COUNT = 65535;
    
    /**
     * Max liczba kolumn w arkuszu (ograniczenie formatu .xls)
     * @var int
     */
    const MAX_COLS_COUNT = 255;
    
    /**
     * Max liczba znakow dla labela
     * @var int
     */
    const MAX_LABEL_LENGTH = 255;
    
    /**
     * Predefiniowane stale z typami danych dla okreslania formatu 
     * dla metody pack()
     * @var string
     */
    const PACK_SHORT  = 'PACK_SHORT';
    const PACK_LONG   = 'PACK_LONG';
    const PACK_DOUBLE = 'PACK_DOUBLE';
    const PACK_CHAR   = 'PACK_CHAR';
    
    /**
     * Indeks rekordu XF
     * @var unsigned short
     */
    const XF_INDEX = 0x000F;
    
    /**
     * Identyfikator rekordu BIFF ustawiajacego znacznik poczatku pliku
     * Struktura rekordu ze znacznikiem poczatku pliku:
     * 
     * Offset    Field_name    Size    Contents
     * ----------------------------------------------------------------
     * 0         vers          1       version:
     *                                 = 00 BIFF2
     *                                 = 02 BIFF3
     *                                 = 04 BIFF4
     *                                 = 08 BIFF5/BIFF7/BIFF8
     * 1         bof           1       09h
     * 
     * Wybrana zostala wersja BIFF8 z uwagi na obsluge Unicode (UTF-16)
     * 
     * @var unsigned short
     */
    const BOF = 0x0809;
    
    /**
     * Stale z wartosciami numeru wersji BIFF:
     * 
     * 0x0500    BIFF5
     * 0x0600    BIFF8
     * 
     * @var unsigned short
     */
    const VERS_BIFF5 = 0x0500;
    const VERS_BIFF8 = 0x0600;
    
    /**
     * Stale z wartosciami poszczegolnych typow strumienia:
     * 
     * 0x0005    workbook globals
     * 0x0006    Visual Basic mode
     * 0x0010    worksheet or dialog sheet
     * 0x0020    chart
     * 0x0040    Excel 4.0 macro sheet
     * 0x0100    workspace file
     * 
     * @var unsigned short
     */
    const DT_WORKBOOK             = 0x0005;
    const DT_VISUAL_BASIC_MODE    = 0x0006;
    const DT_WORKSHEET            = 0x0010;
    const DT_CHART                = 0x0020;
    const DT_EXCEL_40_MACRO_SHEET = 0x0040;
    const DT_WORKSPACE            = 0x0100;
    
    /**
     * Stale z identyfikatorem oraz rokiem builda dla MS Excel 97
     * @var unsigned short
     */
    const RUP_BUILD_EXCEL97 = 0x0DBB;
    const RUP_YEAR_EXCEL97  = 0x07CC;
    
    /**
     * Identyfikator rekordu BIFF ustawiajacego znacznik konca pliku
     * @var unsigned short
     */
    const EOF = 0x0A;
    
    /**
     * Identyfikator rekordu BIFF ustawiajacego wybrana strone kodowa
     * @var unsigned short
     */
    const CODE_PAGE = 0x0042;
    
    /**
     * Stale z wartosciami poszczegolnych stron kodowych:
     * 
     * 0x016F    ASCII
     * 0x04B0    UTF-16 (BIFF8)
     * 0x04E2    Windows CP-1250 (Latin II)
     * 0x04E3    Windows CP-1251 (Cyrylic)
     * 0x04E4    ASNI Windows / Windows CP-1252 (Latin I)
     * 0x04E5    Windows CP-1253 (Greek)
     * 0x04E6    Windows CP-1254 (Turkish)
     * 0x04E7    Windows CP-1255 (Hebrew)
     * 0x04E8    Windows CP-1256 (Arabic)
     * 0x04E9    Windows CP-1257 (Baltic)
     * 0x04EA    Windows CP-1258 (Vietnamese)
     * 0x0551    Windows CP-1361 (Korean)
     * 
     * @var usigned short 
     */
    const ASCII_CODE_PAGE  = 0x016F;
    const UTF16_CODE_PAGE  = 0x04B0;
    const CP1250_CODE_PAGE = 0x04E2;
    const CP1251_CODE_PAGE = 0x04E3;
    const CP1252_CODE_PAGE = 0x04E4;
    const CP1253_CODE_PAGE = 0x04E5;
    const CP1254_CODE_PAGE = 0x04E6;
    const CP1255_CODE_PAGE = 0x04E7;
    const CP1256_CODE_PAGE = 0x04E8;
    const CP1257_CODE_PAGE = 0x04E9;
    const CP1258_CODE_PAGE = 0x04EA;
    const CP1361_CODE_PAGE = 0x0551;
    
    /**
     * Identyfikator rekordu ustawiajacego pusta komorke (typ BLANK) w arkuszu
     * @var unsigned short
     */
    const BLANK_CELL = 0x0201;
    
    /**
     * Identyfikator rekordu wstawiajacego wartosc liczbowa (typ NUMBER - IEEE 
     * floating-point number) do komorki w arkuszu 
     * @var unsigned short
     */
    const NUMBER_CELL = 0x0203;
    
    /**
     * Identyfikator rekordu wstawiajacego ciag znakow (typ LABEL) do komorki 
     * w arkuszu
     * @var unsigned short
     */
    const STRING_CELL = 0x0204;
    
    /**
     * Wartosci flagi grbit dla ciagu znakow (LABEL)
     * @var unsigned short
     */
    const GRBIT_F_HIGH_BYTE_COMPRESSED     = 0x0000;
    const GRBIT_F_HIGH_BYTE_NOT_COMPRESSED = 0x0001;
    
    /**
     * Typ endian:
     * - little - Little-Endian, 
     * - big - Big-Endian, 
     * - unknown - Machine Byte Order
     * @var string
     */
    private $endian_mode = null;
    
    /**
     * Format ciagu znakow dla pakowania za pomoca pack() - typ unsigned short 
     * @var array
     */
    private $pack_short = array(
        'little'  => 'v', 
        'big'     => 'n', 
        'unknown' => 'S'
    );
    
    /**
     * Format ciagu znakow dla pakowania za pomoca pack() - typ unsigned long
     * @var array
     */
    private $pack_long = array(
        'little'  => 'V', 
        'big'     => 'N', 
        'unknown' => 'L'
    );
    
    /**
     * Format ciagu znakow dla pakowania za pomoca pack() - typ unsigned char
     * @var array
     */
    private $pack_char = array(
        'little'  => 'C', 
        'big'     => 'C', 
        'unknown' => 'C'
    );
    
    /**
     * Format ciagu znakow dla pakowania za pomoca pack() - typ double
     * @var array
     */
    private $pack_double = array(
        'little'  => 'd', 
        'big'     => 'd', 
        'unknown' => 'd'
    );
    
    /**
     * Wskaznik do pliku gdzie zapisany zostanie arkusz MS Excel
     * @var resource
     */
    private $fp = null;
    
    /**
     * Numer ostatnio wstawionego wiersza
     * @var long
     */
    private $row_number = 0;
    
    /**
     * konstruktor
     * @param string $pFilePath            sciezka do pliku gdzie zostanie 
     *                                     zapisany arkusz
     */
    public function __construct($pFilePath = null)
    {
        // rozpoznajemy tryb endian dla pozniejszego okreslenia formatu 
        // dla metody pack()
        $this->endian_mode = self::getEndianMode();
        
        if ( !empty($pFilePath))
        {
            $this->fp = fopen($pFilePath, 'w+b');
        }

        // ustawiamy znacznik poczatku pliku
        $this->addBof();
        
        $this->row_number = 0;
    }
    
    public function __destruct()
    {
        $this->close();
    }
    
    /**
     * Pobranie numeru wiersza ostatnio wstawionego za pomoca 
     * SimpleExcelStreamWriter::addNextRow()
     * @return long                        numer ostatnio wstawionego wiersza
     */
    public function getRowCount()
    {
        return $this->row_number;
    }
    
    /**
     * Sprawdzanie poprawnosci parametrow
     * @param string $pMethodName          nazwa metody, z ktorej wywolano
     *                                     sprawdzanie parametrow
     * @param array $pParametersToCheck    parametry do sprawdzenia
     * @return boolean                     true, w przypadku bledu generowany 
     *                                     jest trigger_error
     */
    private function validateParameters($pMethodName = '', 
            array $pParametersToCheck = null)
    {
        $err_msg = null;
        
        if (empty($pMethodName))
        {
            $pMethodName = __METHOD__;
            $err_msg = 'Method name should not be empty!';
        }
        
        foreach ($pParametersToCheck as $key => $value)
        {
            switch ($key)
            {
                case 'endian_mode':
                    if (empty($value))
                    {
                        $err_msg = $key . ' should not be empty!';
                    }
                    
                    $endian_modes = array(
                        'little', 
                        'big',
                        'unknown'
                    );
                    if ( !in_array($value, $endian_modes))
                    {
                        $err_msg = $key . ' not match!';
                    }
                    break;
                    
                case 'pack_mode':
                    if (empty($value) or !is_string($value))
                    {
                        $err_msg = $key . ' should not be empty!';
                    }
                    
                    $pack_modes = array(
                        self::PACK_SHORT, 
                        self::PACK_LONG, 
                        self::PACK_DOUBLE, 
                        self::PACK_CHAR
                    );
                    if ( !in_array($value, $pack_modes))
                    {
                        $err_msg = $key . ' not match!';
                    }
                    break;

                case 'pack_repeat':
                    if ( !is_numeric($value) or $value <= 0)
                    {
                        $err_msg = $key . ' should be a numeric value! ' 
                            . 'Given: ' . $value;
                    }
                    break;

                case 'record':
                    if ( !is_numeric($value))
                    {
                        $err_msg = $key . ' should be a numeric value! ' 
                            . 'Given: ' . $value;
                    }
                    break;

                case 'row':
                    if ( !is_numeric($value) or $value < 0)
                    {
                        $err_msg = $key . ' should be a numeric value! '
                            . 'Given: ' . $value;
                    }
                    if ($value > self::MAX_ROWS_COUNT)
                    {
                        $err_msg = $key . ' value is too big - '
                            . self::MAX_ROWS_COUNT . ' rows are allowed '
                            . ' in xls file! Given: ' . $value; 
                    }
                    break;

                case 'col':
                    if ( !is_numeric($value) or $value < 0)
                    {
                        $err_msg = $key . ' should be a numeric value! '
                            . 'Given: ' . $value;
                    }
                    if ($value > self::MAX_COLS_COUNT)
                    {
                        $err_msg = $key . ' value is too big - '
                            . self::MAX_COLS_COUNT . ' cols are allowed '
                            . 'in xls file! Given: ' . $value;
                    }
                    break;                    
            }
        }
        
        if ( !empty($err_msg))
        {
            trigger_error($pMethodName . '(): ' . $err_msg, E_USER_ERROR);
        }

        return true;
    }
    
    /**
     * Wykrywanie trypu endian (Little-Endian, Big-Endian, Machine Byte Order)
     * @return string                      tryb endian {litte, big, unknown}
     */
    public static function getEndianMode()
    {
        // domyslnie Machine Byte Order
        $endian_mode = 'unknown';
    
        // 'abyz' w postaci szesnastkowej
        $abyz = 0x6162797A;
    
        // konwersja $abyz do 32 bitowej postaci binarnej
        // L - unsigned long (32 bit, machine byte order)
        switch (pack ('L', $abyz))
        {
            case pack ('V', $abyz):        // Little-Endian
                $endian_mode = 'little';
                break;
    
            case pack ('N', $abyz):        // Big-Endian
                $endian_mode = 'big';
                break;
    
            default:
                $endian_mode = 'unknown';
                break;
        }
    
        return $endian_mode;
    }
    
    /**
     * Pobranie formatu dla metody pack() w zaleznosci od rozpoznanego 
     * trybu endian
     * @param stirng $pMode                tryb mode:
     *                                     - self::PACK_SHORT, 
     *                                     - self::PACK_LONG, 
     *                                     - self::PACK_DOUBLE, 
     *                                     - self::PACK_CHAR
     * @param int $pRepeat                 liczba powtorzen formatu w danym 
     *                                     trybie endian (opcjonalna)
     * @return string                      format dla metody pack podanego typu
     *                                     oraz liczby powtorzen
     */
    private function getPackFormat($pMode, $pRepeat = 1)
    {
        $parameters_to_check = array(
            'endian_mode' => $this->endian_mode, 
            'pack_mode'   => $pMode, 
            'pack_repeat' => $pRepeat
        );
        $this->validateParameters(__METHOD__, $parameters_to_check);
    
        // tablica z mapowaniem trybu endian na odpowiedni format
        // dla metody pack()
        $pack_map_table = array();
        switch ($pMode)
        {
            case self::PACK_SHORT:
                $pack_map_table = $this->pack_short;
                break;
                
            case self::PACK_LONG:
                $pack_map_table = $this->pack_long;
                break;      

            case self::PACK_DOUBLE:
                $pack_map_table = $this->pack_double;
                break;
                
            case self::PACK_CHAR:
                $pack_map_table = $this->pack_char;
                break;

            default:
                $pack_map_table = $this->pack_short;
                break;
        }
        
        $pack_format = null;
        if ( !empty($pack_map_table[$this->endian_mode]))
        {
            $pack_format = $pack_map_table[$this->endian_mode];
            if ( !empty($pack_format) and $pRepeat > 1)
            {
                $pack_format = str_repeat($pack_format, $pRepeat);
            }
        }
    
        return $pack_format;
    }
    
    /**
     * Dodanie rekordu do arkusza.
     * Wszystkie rekordy BIFF maja nastepujaca strukture:
     * 
     * Offset    Length (bytes)    Contents
     * ----------------------------------------------------------------
     * 0         2                 record number (16-bit word identifies 
     *                             the record)
     * 2         2                 record data length (16-bit word 
     *                             equals the length 
     *                             of the following record data)
     * 4         var               record data
     * 
     * @param unsigned short $pRecord      identyfikator wstawianego rekordu
     * @param string $pData                wstawiane dane do arkusza
     * @return boolean                     czy wstawiono dane do arkusza
     */
    private function addRecord($pRecord, $pData = null)
    {
        $this->validateParameters(__METHOD__, array('record' => $pRecord));
        
        // dlugosc wstawianych danych
        $length = !empty($pData) ? strlen($pData) : 0x0000;
        // naglowek
        $pack_format = $this->getPackFormat(self::PACK_SHORT, 2);
        $header = pack($pack_format, $pRecord, $length);
        
        // rekord BIFF sklada sie z naglowka (header, bity 0-3) oraz danych
        $record = $header . ( !empty($pData) ? $pData : '');
        if ($this->fp and is_resource($this->fp))
        {
            if (fputs($this->fp, $record) > 0)
            {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Ustawienie znacznika poczatku pliku w formacie BIFF - BOF 
     * (Beginning of File) 
     *
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
     *
     * @return bool                        czy udalo sie ustawic BOF
     */
    private function addBof()
    {
        // numer wersji (BIFF8 - obsluga Unicode)
        $vers      = self::VERS_BIFF8;           
        // typ (worksheet - pojedynczy arkusz)
        $dt        = self::DT_WORKSHEET;         
        // identyfikator builda (Excel 97)
        $rup_build = self::RUP_BUILD_EXCEL97;    
        // build year (Excel 97)
        $rup_year  = self::RUP_YEAR_EXCEL97;     
    
        // wartosci bfh oraz sfo "podejrzane" na rzeczywistych 
        // plikach MS Excel 2007
        $bfh = 0x000100D1;
        $sfo = 0x00000406;
    
        $pack_format = $this->getPackFormat(self::PACK_SHORT, 4)
            . $this->getPackFormat(self::PACK_LONG, 2);
        $data = pack($pack_format, $vers, $dt, $rup_build, $rup_year, $bfh, $sfo);
    
        return $this->addRecord(self::BOF, $data);
    }
    
    /**
     * Ustawienie znacznika konca pliku w formacie BIFF - EOF (End of File)
     * @return boolean                     czy udalo sie ustawic EOF
     */
    private function addEof()
    {
        return $this->addRecord(self::EOF, null);
    }
    
    /**
     * Dodanie rekordu z ustawieniami strony kodowej (UTF-16).
     * 
     * Identyfikatory strony kodowej:
     *
     * 0x016F    ASCII
     * 0x04B0    UTF-16 (BIFF8)
     * 0x04E2    Windows CP-1250 (Latin II)
     * 0x04E3    Windows CP-1251 (Cyrylic)
     * 0x04E4    ASNI Windows / Windows CP-1252 (Latin I)
     * 0x04E5    Windows CP-1253 (Greek)
     * 0x04E6    Windows CP-1254 (Turkish)
     * 0x04E7    Windows CP-1255 (Hebrew)
     * 0x04E8    Windows CP-1256 (Arabic)
     * 0x04E9    Windows CP-1257 (Baltic)
     * 0x04EA    Windows CP-1258 (Vietnamese)
     * 0x0551    Windows CP-1361 (Korean)
     *
     * @return boolean                     czy udalo sie dodac rekord do arkusza
     */
    private function addCodePage()
    {
        $pack_format = $this->getPackFormat(self::PACK_SHORT);
        $data = pack($pack_format, self::UTF16_CODE_PAGE);
    
        return $this->addRecord(self::CODE_PAGE, $data);
    }
    
    /**
     * Dodanie pustej komorki do arkusza pod podanym adresem ($pRow, $pCol)
     *
     * Struktura danych (offset 0-3 to naglowek) dla pustej komorki (BLANK):
     *
     * Offset    Name    Size    Contents
     * ----------------------------------------------
     * 4         rw      2       row
     * 6         col     2       column
     * 8         ixfe    2       index to the XF record
     *
     * @param long $pRow                   numer wiersza komorki
     *                                     (0..self::MAX_ROWS_COUNT)
     * @param int $pCol                    numer kolumny komorki
     *                                     (0..self::MAX_COLS_COUNT)
     * @return boolean                     czy udalo sie dodac pusta komorke 
     *                                     do arkusza pod podanym adresem
     */
    private function addBlankCell($pRow = 0, $pCol = 0)
    {
        $parameters_to_check = array('row' => $pRow, 'col' => $pCol);
        $this->validateParameters(__METHOD__, $parameters_to_check);
    
        $pack_format = $this->getPackFormat(self::PACK_SHORT, 3); 
        $data = pack($pack_format, $pRow, $pCol, self::XF_INDEX);
    
        return $this->addRecord(self::BLANK_CELL, $data);
    }
    
    /**
     * Pobranie wartosci liczbowej do wstawienia do arkusza.
     * W razie potrzeby (odpowiedni tryb endian) zamieniania jest 
     * sekwencja bitow
     * @param double $pValue               wartosc liczbowa do wstawienia
     *                                     do arkusza
     * @return string                      wartosc liczbowa w formacie BIFF
     */
    private function getNumberValue($pValue = 0)
    {
        $parameters_to_check = array('endian_mode' => $this->endian_mode);
        $this->validateParameters(__METHOD__, $parameters_to_check);
    
        $value = pack($this->getPackFormat(self::PACK_DOUBLE), $pValue);
        
        // jesli Big-Endian trzeba zamienic kolejnosc
        if ('big' == $this->endian_mode)    
        {
            $value = strrev($value);
        }
    
        return $value;
    }
    
    /**
     * Dodanie komorki z wartoscia liczbowa do arkusza pod podanym 
     * adresem ($pRow, $pCol)
     *
     * Struktura danych (offset 0-3 to naglowek) dla komorki z wartoscia 
     * liczbowa (NUMBER):
     *
     * Offset    Name    Size    Contents
     * ----------------------------------------------
     * 4         rw      2       row
     * 6         col     2       column
     * 8         ixfe    2       index to the XF record
     * 10        num     8       floating-point value
     *
     * @param long $pRow                   numer wiersza komorki
     *                                     (0..self::MAX_ROWS_COUNT)
     * @param int $pCol                    numer kolumny komorki
     *                                     (0..self::MAX_COLS_COUNT)
     * @param double $pValue               wartosc liczbowa wstawiana do 
     *                                     komorki o podanym adresie 
     *                                     ($pRow, $pCol)
     * @return boolean                     czy udalo sie dodac komorke z 
     *                                     wartoscia liczbowa do arkusza 
     *                                     pod podanym adresem
     */
    private function addNumberCell($pRow = 0, $pCol = 0, $pValue = 0)
    {
        $parameters_to_check = array('row' => $pRow, 'col' => $pCol);
        $this->validateParameters(__METHOD__, $parameters_to_check);
    
        $pack_format = $this->getPackFormat(self::PACK_SHORT, 3);
        $data = pack($pack_format, $pRow, $pCol, self::XF_INDEX) 
            . $this->getNumberValue($pValue);
    
        return $this->addRecord(self::NUMBER_CELL, $data);
    }
    
    /**
     * Konwersja ciagu UTF-8 do postaci 16 bitowego ciagu BIFF8 Unicode
     * Zwracany bedzie ciag nieskompresowany (no rich text, no Asian 
     * phonetics)
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
     * @param string $pStr                 ciag wejsciowy w UTF-8
     * @return string                      ciag wejsciowy w postaci BIFF8 Unicode
     */
    private function getStringValue($pStr)
    {
        // liczba znakow
        $ln = mb_strlen($pStr, 'UTF-8');
        // format xls ma ograniczenie do 255 znakow dla LABEL
        if ($ln >= self::MAX_LABEL_LENGTH)
        {
            $ln = self::MAX_LABEL_LENGTH;
            $pStr = mb_substr($pStr, 0, $ln, 'UTF-8');
        }
        // zmiana kodowania na Unicode (UTF-16LE)
        $chars = mb_convert_encoding($pStr, 'UTF-16LE', 'UTF-8');
        
        $pack_format = $this->getPackFormat(self::PACK_SHORT)
            . $this->getPackFormat(self::PACK_CHAR); 
        
        return pack($pack_format, $ln, self::GRBIT_F_HIGH_BYTE_NOT_COMPRESSED) . $chars;
    }
    
    /**
     * Dodanie komorki z ciagiem znakow do arkusza pod podanym 
     * adresem ($pRow, $pCol)
     *
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
     *
     * @param long $pRow                   numer wiersza komorki
     *                                     (0..self::MAX_ROWS_COUNT)
     * @param int $pCol                    numer kolumny komorki
     *                                     (0..self::MAX_COLS_COUNT)
     * @param string $pValue               ciag znakow wstawiany do komorki
     *                                     pod podanym adresem ($pRow, $pCol)
     * @return boolean                     czy udalo sie dodac komorke z ciagiem
     *                                     znakow do arkusza pod podanym adresem
     */
    private function addStringCell($pRow = 0, $pCol = 0, $pValue = 0)
    {
        $parameters_to_check = array('row' => $pRow, 'col' => $pCol);
        $this->validateParameters(__METHOD__, $parameters_to_check);
    
        $pack_format = $this->getPackFormat(self::PACK_SHORT, 3);
        $data = pack($pack_format, $pRow, $pCol, self::XF_INDEX)
            . $this->getStringValue($pValue);
    
        return $this->addRecord(self::STRING_CELL, $data);
    }
    
    /**
     * Wstawienie wartosci do arkusza do komorki o podanym adresie ($pRow, $pCol)
     * @param long $pRow                   numer wiersza komorki
     *                                     (0..self::MAX_ROWS_COUNT)
     * @param int $pCol                    numer kolumny komorki
     *                                     (0..self::MAX_COLS_COUNT)
     * @param string $pValue               wartosc wstawiana do komorki pod 
     *                                     podanym adresem ($pRow, $pCol)
     * @return boolean                     czy udalo sie wstawic wartosc do 
     *                                     komorki o podanym adresie
     */
    public function addCell($pRow, $pCol, $pValue = null)
    {
        $parameters_to_check = array('row' => $pRow, 'col' => $pCol);
        $this->validateParameters(__METHOD__, $parameters_to_check);
    
        $ret = false;
        if (is_numeric($pValue))
        {
            $ret = $this->addNumberCell($pRow, $pCol, $pValue);
        }
        else if (is_string($pValue) and !empty($pValue))
        {
            $ret = $this->addStringCell($pRow, $pCol, $pValue);
        }
        else
        {
            $ret = $this->addBlankCell($pRow, $pCol);
        }
    
        return $ret;
    }
    
    /**
     * Wstawienie do arkusza wiersza o podanym numerze wiersza
     * @param long                         numer wiersza w arkuszu
     * @param array $pData                 dane do wstawienia
     * @return boolean                     czy udalo sie wstawic dane do arkusza
     */
    public function addRow($pRow = 0, array $pData = null)
    {
        $this->validateParameters(__METHOD__, array('row' => $pRow));
    
        if ( !empty($pData))
        {
            $col = 0;
            foreach (array_values($pData) as $val)
            {
                if (false === $this->addCell($pRow, $col, $val))
                {
                    return false;
                }
    
                $col++;
            }
        }
    
        return true;
    }
    
    /**
     * Wstawienie do arkusza wiesza, wiesz z danymi zostanie dodany w nastepnym
     * wieszu po ostanio wstawionym wierszu do arkusza
     * @param array $pData                 dane do wstawienia
     * @return boolean                     czy udalo sie wstawic dane do arkusza
     */
    public function addNextRow(array $pData = null)
    {
        if (false === $this->addRow($this->row_number, $pData))
        {
            return false;
        }
        
        $this->row_number++;
        
        return true;
    }
    
    /**
     * Ustawienie znacznika konca pliku (EOF) oraz zamkniecie deskryptora pliku
     */
    public function close()
    {
        // ustawiamy znacznik konca pliku (EOF)
        $this->addEof();
            
        if ($this->fp and is_resource($this->fp))
        {
            fclose($this->fp);
        }
        
        $this->fp = null;
        $this->row_number = 0;
    }
}
?>
