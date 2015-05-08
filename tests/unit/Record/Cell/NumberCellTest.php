<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Cell;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\Cell\NumberCell;

class NumberCellTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndianZeroValueNumberCellRecord()
    {
        $record = new NumberCell($this->getLittleEndianPackFormatter(), 0, 0, 0);
        $this->assertEquals(hex2bin('03020e00000000000f000000000000000000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianNonZeroValueNumberCellRecord()
    {
        $record = new NumberCell($this->getLittleEndianPackFormatter(), 0, 0, 999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8f40'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianNegativeValueNumberCellRecord()
    {
        $record = new NumberCell($this->getLittleEndianPackFormatter(), 0, 0, -999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8fc0'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianZeroValueNumberCellRecord()
    {
        $record = new NumberCell($this->getBigEndianPackFormatter(), 0, 0, 0);
        $this->assertEquals(hex2bin('0203000e00000000000f0000000000000000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianNonZeroValueNumberCellRecord()
    {
        $record = new NumberCell($this->getBigEndianPackFormatter(), 0, 0, 999.99);
        $this->assertEquals(hex2bin('0203000e00000000000f408f3feb851eb852'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianNegativeValueNumberCellRecord()
    {
        $record = new NumberCell($this->getBigEndianPackFormatter(), 0, 0, -999.99);
        $this->assertEquals(hex2bin('0203000e00000000000fc08f3feb851eb852'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianZeroValueNumberCellRecord()
    {
        $record = new NumberCell($this->getMachineByteOrderEndianPackFormatter(), 0, 0, 0);
        $this->assertEquals(hex2bin('03020e00000000000f000000000000000000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianNonZeroValueNumberCellRecord()
    {
        $record = new NumberCell($this->getMachineByteOrderEndianPackFormatter(), 0, 0, 999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8f40'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianNegativeValueNumberCellRecord()
    {
        $record = new NumberCell($this->getMachineByteOrderEndianPackFormatter(), 0, 0, -999.99);
        $this->assertEquals(hex2bin('03020e00000000000f0052b81e85eb3f8fc0'), $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenRowIsInvalid()
    {
        $record = new NumberCell($this->getMachineByteOrderEndianPackFormatter(), -100, 0, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenMaxdRowExceeded()
    {
        $record = new NumberCell($this->getMachineByteOrderEndianPackFormatter(), 99999, 0, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenColIsInvalid()
    {
        $record = new NumberCell($this->getMachineByteOrderEndianPackFormatter(), 0, -100, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataException
     */
    public function shouldThrowInvalidRecordDataExceptionWhenMaxColExceeded()
    {
        $record = new NumberCell($this->getMachineByteOrderEndianPackFormatter(), 0, 999, 0);
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordDataValueException
     */
    public function shouldThrowInvalidRecordDataValueExceptionWhenCellValueIsInvalid()
    {
        $record = new NumberCell($this->getMachineByteOrderEndianPackFormatter(), 0, 0, 'string');
        $this->assertEquals('', $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordNumberException
     */
    public function shouldThrowInvalidRecordNumberExceptionWhenRecordNumberIsInvalid()
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
