<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Cell;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\Cell\NumberCell;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

class NumberCellTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function getRecordLittleEndianZeroValue()
    {
        $record = new NumberCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, 0);
        $this->assertEquals(hex2bin('03020e00000000000f000000000000000000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordLittleEndianNonZeroValue()
    {
        $record = new NumberCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, 999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8f40'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordLittleEndianNegativeValue()
    {
        $record = new NumberCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, -999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8fc0'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordBigEndianZeroValue()
    {
        $record = new NumberCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, 0);
        $this->assertEquals(hex2bin('0203000e00000000000f0000000000000000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordBigEndianNonZeroValue()
    {
        $record = new NumberCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, 999.99);
        $this->assertEquals(hex2bin('0203000e00000000000f408f3feb851eb852'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordBigEndianNegativeValue()
    {
        $record = new NumberCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, -999.99);
        $this->assertEquals(hex2bin('0203000e00000000000fc08f3feb851eb852'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordMachineByteOrderEndianZeroValue()
    {
        $record = new NumberCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, 0);
        $this->assertEquals(hex2bin('03020e00000000000f000000000000000000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordMachineByteOrderEndianNonZeroValue()
    {
        $record = new NumberCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, 999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8f40'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordMachineByteOrderEndianNegativeValue()
    {
        $record = new NumberCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, -999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8fc0'), $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function getRecordInvalidRow()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), -100, 0, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function getRecordMaxdRowExceeded()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), 99999, 0, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function getRecordInvalidCol()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), 0, -100, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function getRecordMaxColExceeded()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), 0, 999, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataValueException
     */
    public function getRecordInvalidCellValue()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), 0, 0, 'string');
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordNumberException
     */
    public function getRecordWithInvalidRecordNumber()
    {
        $record = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\Record\Cell\NumberCell', [
            'getRecordNumber'
        ]);
        
        $record->expects($this->any())
            ->method('getRecordNumber')
            ->willReturn('');
        
        $record->getRecord();
    }
}
