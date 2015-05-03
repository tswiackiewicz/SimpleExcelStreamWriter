<?php
namespace TSwiackiewicz\ExcelStreamWriter;

use TSwiackiewicz\ExcelStreamWriter\Record\RecordFactory;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;
use TSwiackiewicz\ExcelStreamWriter\Record\Record;

/**
 * Klasa umozliwiajaca strumieniowy zapis plikow w formacie xls (BIFF - Binary Interchange File Format)
 */
class ExcelStreamWriter
{

    /**
     * Sciezka do pliku wynikowego
     * 
     * @var string
     */
    private $path;

    /**
     * Fabryka rekordow
     * 
     * @var RecordFactory
     */
    private $factory;

    /**
     * Uchwyt do otwartego pliku wynikowego
     * 
     * @var resource
     */
    private $fileHandle;

    /**
     * Licznik ostatniego dodanego wiersza za pomoca ExcelStreamWriter::addNextRow()
     * 
     * @var integer
     */
    private $rowsCount = 0;

    /**
     * Czy writer zostal otwarty
     * 
     * @var boolean
     */
    private $writerOpened = false;

    /**
     * Czy writer zostal zamkniety
     * 
     * @var boolean
     */
    private $writerClosed = false;

    /**
     * Inicjalizacja writera
     * 
     * @param string $path sciezka do pliku wynikowego
     * @param RecordFactory $factory fabryka rekordow (opcjonalna)
     */
    public function __construct($path, RecordFactory $factory = null)
    {
        $this->path = $path;
        $this->factory = $factory;
        if (is_null($factory)) {
            $this->factory = new RecordFactory(new PackFormatter(new ByteOrder()));
        }
    }

    /**
     * Zamkniecie writera (jesli nie zostal zamkniety)
     */
    public function __destruct()
    {
        if ($this->writerOpened and !$this->writerClosed) {
            $this->close();
        }
    }

    /**
     * Ustawienie fabryki rekordow
     * 
     * @param RecordFactory $factory fabryka rekordow
     */
    public function setFactory(RecordFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Otwarcie writera
     * 
     * @throws \Exception blad otwarcia pliku do zapisu
     */
    public function open()
    {
        if (empty($this->path)) {
            throw new \Exception();
        }
        
        $this->fileHandle = fopen($this->path, 'w+b');
        if (false === $this->fileHandle) {
            throw new \Exception('Unable to open file: ' . $this->path . '!');
        }
        
        // ustawiamy znacznik poczatku pliku (BOF)
        $this->writeRecord($this->factory->getBof());
        
        $this->writerOpened = true;
    }

    /**
     * Zamkniecie writera
     * 
     * @throws \Exception blad zamkniecia pliku
     */
    public function close()
    {
        // ustawiamy znacznik konca pliku (EOF)
        $this->writeRecord($this->factory->getEof());
        
        if (false === fclose($this->fileHandle)) {
            throw new \Exception('Unable to close file: ' . $this->path . '!');
        }
        
        $this->writerClosed = true;
    }

    /**
     * Dodanie nowego wiersza do arkusza
     * 
     * @param array $data wstawiany wiersz z danymi
     * @return boolean czy dodano nowy wiersz do arkusza
     */
    public function addNextRow(array $data)
    {
        if (false === $this->addRow($this->rowsCount, $data)) {
            return false;
        }
        
        $this->rowsCount++;
        
        return true;
    }

    /**
     * Dodanie nowego wiersza o podanym numerze do arkusza
     * 
     * @param integer $row numer wiersza
     * @param array $data wstawiany wiersz z danymi
     * @return boolean czy dodano wiersz do arkusza
     */
    public function addRow($row, array $data)
    {
        $col = 0;
        foreach (array_values($data) as $value) {
            if (false === $this->addCell($row, $col, $value)) {
                return false;
            }
            
            $col++;
        }
        
        return true;
    }

    /**
     * Dodanie nowej komorki do arkusza
     * 
     * @param integer $row numer wiersza komorki
     * @param integer $col numer kolumny komorki
     * @param string $value wartosc wstawiana do komorki
     * @return boolean czy dodano komorke do arkusza
     */
    public function addCell($row, $col, $value)
    {
        // otwarcie writera (jesli nie zostal otwarty)
        if (!$this->writerOpened) {
            $this->open();
        }
        
        if (is_numeric($value)) {
            return $this->writeRecord($this->factory->getNumberCell($row, $col, $value));
        }
        
        if (is_string($value) and !empty($value)) {
            return $this->writeRecord($this->factory->getStringCell($row, $col, $value));
        }
        
        return $this->writeRecord($this->factory->getBlankCell($row, $col));
    }

    /**
     * Zapisanie rekordu w arkuszu
     * 
     * @param Record $record zapisywany rekord
     * @throws \Exception blad zapisu do pliku
     * @return boolean
     */
    private function writeRecord(Record $record)
    {
        if (!$this->fileHandle or !is_resource($this->fileHandle)) {
            throw new \Exception('Invalid file handle!');
        }
        
        if (false === fputs($this->fileHandle, $record->getRecord())) {
            throw new \Exception('Unable to write data to file!');
        }
        
        return true;
    }
}
