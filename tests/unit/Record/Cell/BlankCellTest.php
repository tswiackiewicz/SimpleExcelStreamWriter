<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Cell;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\Cell\BlankCell;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

class BlankCellTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndianBlankCellRecord()
    {
        $record = new BlankCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0);
        $this->assertEquals(hex2bin('01020600000000000f00'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianBlankCellRecord()
    {
        $record = new BlankCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0);
        $this->assertEquals(hex2bin('0201000600000000000f'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianBlankCellRecord()
    {
        $record = new BlankCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0);
        $this->assertEquals(hex2bin('01020600000000000f00'), $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenInvalidRowIsSet()
    {
        $record = new BlankCell(new PackFormatter(new ByteOrder()), -100, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenMaxRowExceeded()
    {
        $record = new BlankCell(new PackFormatter(new ByteOrder()), 99999, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenInvalidColIsSet()
    {
        $record = new BlankCell(new PackFormatter(new ByteOrder()), 0, -100);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenMaxColExceeded()
    {
        $record = new BlankCell(new PackFormatter(new ByteOrder()), 0, 999);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordNumberException
     */
    public function shouldThrowInvalidRecordNumberExceptionWhenRecordNumberIsInvalid()
    {
        $record = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\Record\Cell\BlankCell', [
            'getRecordNumber'
        ]);
        
        $record->expects($this->any())
            ->method('getRecordNumber')
            ->willReturn('');
        
        $record->getRecord();
    }
}
