<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Cell;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\Cell\StringCell;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

class StringCellTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndianEmptyValueStringCellRecord()
    {
        $record = new StringCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, '');
        $this->assertEquals(hex2bin('04020900000000000f00000001'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianNonEmptyValueStringCellRecord()
    {
        $record = new StringCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, 'test');
        $this->assertEquals(hex2bin('04021100000000000f000400017400650073007400'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianNonEmptyUnicodeValueStringCellRecord()
    {
        $record = new StringCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, 'zażółć gęślą jaźń');
        $this->assertEquals(hex2bin('04022b00000000000f001100017a0061007c01f300420107012000670019015b016c00050120006a0061007a014401'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianEmptyValueStringCellRecord()
    {
        $record = new StringCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, '');
        $this->assertEquals(hex2bin('0204000900000000000f000001'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianNonEmptyValueStringCellRecord()
    {
        $record = new StringCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, 'test');
        $this->assertEquals(hex2bin('0204001100000000000f0004017400650073007400'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianNonEmptyUnicodeValueStringCellRecord()
    {
        $record = new StringCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, 'zażółć gęślą jaźń');
        $this->assertEquals(hex2bin('0204002b00000000000f0011017a0061007c01f300420107012000670019015b016c00050120006a0061007a014401'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianEmptyValueStringCellRecord()
    {
        $record = new StringCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, '');
        $this->assertEquals(hex2bin('04020900000000000f00000001'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianNonEmptyValueStringCellRecord()
    {
        $record = new StringCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, 'test');
        $this->assertEquals(hex2bin('04021100000000000f000400017400650073007400'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianNonEmptyUnicodeValueStringCellRecord()
    {
        $record = new StringCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, 'zażółć gęślą jaźń');
        $this->assertEquals(hex2bin('04022b00000000000f001100017a0061007c01f300420107012000670019015b016c000501a0006a0061007a014401'), $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenInvalidRow()
    {
        $record = new StringCell(new PackFormatter(new ByteOrder()), -100, 0, '');
        $record->getRecord();
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenMaxdRowExceeded()
    {
        $record = new StringCell(new PackFormatter(new ByteOrder()), 99999, 0, '');
        $record->getRecord();
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenInvalidCol()
    {
        $record = new StringCell(new PackFormatter(new ByteOrder()), 0, -100, '');
        $record->getRecord();
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenMaxColExceeded()
    {
        $record = new StringCell(new PackFormatter(new ByteOrder()), 0, 999, '');
        $record->getRecord();
    }

    /**
     * @test
     */
    public function shouldReturnShortenLittleEndianStringCellRecordWhenMaxLabelLenghtExceeded()
    {
        $record = new StringCell(new PackFormatter(new LittleEndianByteOrderMock()), 0, 0, str_repeat('X', 999));
        $this->assertEquals(hex2bin('04020702000000000f00ff0001580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnShortenBigEndianStringCellRecordWhenMaxLabelLenghtExceeded()
    {
        $record = new StringCell(new PackFormatter(new BigEndianByteOrderMock()), 0, 0, str_repeat('X', 999));
        $this->assertEquals(hex2bin('0204020700000000000f00ff01580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnShortenMachineByteOrderEndianStringCellRecordWhenMaxLabelLenghtExceeded()
    {
        $record = new StringCell(new PackFormatter(new MachineByteOrderByteOrderMock()), 0, 0, str_repeat('X', 999));
        $this->assertEquals(hex2bin('04020702000000000f00ff0001580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800580058005800'), $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordNumberException
     */
    public function shouldThrowInvalidRecordNumberExceptionWhenRecordNumberIsInvalid()
    {
        $record = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\Record\Cell\StringCell', [
            'getRecordNumber'
        ]);
        
        $record->expects($this->any())
            ->method('getRecordNumber')
            ->willReturn('');
        
        $record->getRecord();
    }
}
