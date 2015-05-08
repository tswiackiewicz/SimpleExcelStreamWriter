<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\RecordFactory;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

class RecordFactoryTest extends AbstractTestCase
{

    /**
     * Fabryka rekordow
     * 
     * @var RecordFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new RecordFactory(new PackFormatter(new ByteOrder()));
    }

    protected function tearDown()
    {
        $this->factory = null;
    }

    /**
     * @test
     */
    public function shouldReturnBofInstance()
    {
        $this->assertInstanceOf('TSwiackiewicz\ExcelStreamWriter\Record\Bof\Bof', $this->factory->getBof());
    }

    /**
     * @test
     */
    public function shouldReturnEofInstance()
    {
        $this->assertInstanceOf('TSwiackiewicz\ExcelStreamWriter\Record\Eof\Eof', $this->factory->getEof());
    }

    /**
     * @test
     */
    public function shouldReturnCodePageInstance()
    {
        $this->assertInstanceOf('TSwiackiewicz\ExcelStreamWriter\Record\CodePage\CodePage', $this->factory->getCodePage());
    }

    /**
     * @test
     */
    public function shouldReturnBlankCellInstance()
    {
        $this->assertInstanceOf('TSwiackiewicz\ExcelStreamWriter\Record\Cell\BlankCell', $this->factory->getBlankCell(0, 0));
    }

    /**
     * @test
     */
    public function shouldReturnNumberCellInstance()
    {
        $this->assertInstanceOf('TSwiackiewicz\ExcelStreamWriter\Record\Cell\NumberCell', $this->factory->getNumberCell(0, 0, 0));
    }

    /**
     * @test
     */
    public function shouldReturnStringCellInstance()
    {
        $this->assertInstanceOf('TSwiackiewicz\ExcelStreamWriter\Record\Cell\StringCell', $this->factory->getStringCell(0, 0, ''));
    }
}