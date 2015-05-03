<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Cell;

use TSwiackiewicz\ExcelStreamWriter\Record\Cell\NumberCell;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

class NumberCellTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getRecordZeroValueLittleEndian()
    {
        $record = new NumberCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, 0);
        $this->assertEquals(hex2bin('03020e00000000000f000000000000000000'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNonZeroValueLittleEndian()
    {
        $record = new NumberCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, 999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8f40'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNegativeValueLittleEndian()
    {
        $record = new NumberCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, -999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8fc0'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordZeroValueBigEndian()
    {
        $record = new NumberCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, 0);
        $this->assertEquals(hex2bin('0203000e00000000000f0000000000000000'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNonZeroValueBigEndian()
    {
        $record = new NumberCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, 999.99);
        $this->assertEquals(hex2bin('0203000e00000000000f408f3feb851eb852'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNegativeValueBigEndian()
    {
        $record = new NumberCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, -999.99);
        $this->assertEquals(hex2bin('0203000e00000000000fc08f3feb851eb852'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordZeroValueMachineByteOrderEndian()
    {
        $record = new NumberCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, 0);
        $this->assertEquals(hex2bin('03020e00000000000f000000000000000000'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNonZeroValueMachineByteOrderEndian()
    {
        $record = new NumberCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, 999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8f40'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNegativeValueMachineByteOrderEndian()
    {
        $record = new NumberCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, -999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8fc0'), $record->getRecord());
    }
    
    /**
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     * @expectedExceptionMessage Invalid row number!
     * @test
     */
    public function getRecordInvalidRow()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), -100, 0, 0);
        $this->assertEquals('', $record->getRecord());
    }
    
    /**
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     * @expectedExceptionMessage Invalid row number!
     * @test
     */
    public function getRecordMaxdRowExceeded()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), 99999, 0, 0);
        $this->assertEquals('', $record->getRecord());
    }
    
    /**
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     * @expectedExceptionMessage Invalid col number!
     * @test
     */
    public function getRecordInvalidCol()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), 0, -100, 0);
        $this->assertEquals('', $record->getRecord());
    }
    
    /**
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     * @expectedExceptionMessage Invalid col number!
     * @test
     */
    public function getRecordMaxColExceeded()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), 0, 999, 0);
        $this->assertEquals('', $record->getRecord());
    }
    
    /**
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataValueException
     * @expectedExceptionMessage Invalid value - numeric value is expected!
     * @test
     */
    public function getRecordInvalidCellValue()
    {
        $record = new NumberCell(new PackFormatter(new ByteOrder()), 0, 0, 'string');
        $this->assertEquals('', $record->getRecord());
    }
}
