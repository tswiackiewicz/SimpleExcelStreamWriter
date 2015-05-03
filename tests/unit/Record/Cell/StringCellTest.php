<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Cell;

use TSwiackiewicz\ExcelStreamWriter\Record\Cell\StringCell;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

class StringCellTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getRecordEmptyValueLittleEndian()
    {
        $record = new StringCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, '');
        $this->assertEquals(hex2bin('04020900000000000f00000001'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNonEmptyValueLittleEndian()
    {
        $record = new StringCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, 'test');
        $this->assertEquals(hex2bin('04021100000000000f000400017400650073007400'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNonEmptyUnicodeValueLittleEndian()
    {
        $record = new StringCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, 'zażółć gęślą jaźń');
        $this->assertEquals(hex2bin('04022b00000000000f001100017a0061007c01f300420107012000670019015b016c00050120006a0061007a014401'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordEmptyValueBigEndian()
    {
        $record = new StringCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, '');
        $this->assertEquals(hex2bin('0204000900000000000f000001'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNonEmptyValueBigEndian()
    {
        $record = new StringCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, 'test');
        $this->assertEquals(hex2bin('0204001100000000000f0004017400650073007400'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNonEmptyUnicodeValueBigEndian()
    {
        $record = new StringCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, 'zażółć gęślą jaźń');
        $this->assertEquals(hex2bin('0204002b00000000000f0011017a0061007c01f300420107012000670019015b016c00050120006a0061007a014401'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordEmptyValueMachineByteOrderEndian()
    {
        $record = new StringCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, '');
        $this->assertEquals(hex2bin('04020900000000000f00000001'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNonEmptyValueMachineByteOrderEndian()
    {
        $record = new StringCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, 'test');
        $this->assertEquals(hex2bin('04021100000000000f000400017400650073007400'), $record->getRecord());
    }
    
    /**
     * @test
     */
    public function getRecordNonEmptyUnicodeValueMachineByteOrderEndian()
    {
        $record = new StringCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, 'zażółć gęślą jaźń');
        $this->assertEquals(hex2bin('04022b00000000000f001100017a0061007c01f300420107012000670019015b016c000501a0006a0061007a014401'), $record->getRecord());
    }
    
    /**
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     * @expectedExceptionMessage Invalid row number!
     * @test
     */
    public function getRecordInvalidRow()
    {
        $record = new StringCell(new PackFormatter(new ByteOrder()), -100, 0, '');
        $this->assertEquals('', $record->getRecord());
    }
    
    /**
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     * @expectedExceptionMessage Invalid row number!
     * @test
     */
    public function getRecordMaxdRowExceeded()
    {
        $record = new StringCell(new PackFormatter(new ByteOrder()), 99999, 0, '');
        $this->assertEquals('', $record->getRecord());
    }
    
    /**
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     * @expectedExceptionMessage Invalid col number!
     * @test
     */
    public function getRecordInvalidCol()
    {
        $record = new StringCell(new PackFormatter(new ByteOrder()), 0, -100, '');
        $this->assertEquals('', $record->getRecord());
    }
    
    /**
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     * @expectedExceptionMessage Invalid col number!
     * @test
     */
    public function getRecordMaxColExceeded()
    {
        $record = new StringCell(new PackFormatter(new ByteOrder()), 0, 999, '');
        $this->assertEquals('', $record->getRecord());
    }
}
