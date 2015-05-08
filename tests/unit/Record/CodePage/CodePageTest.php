<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\CodePage;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\CodePage\CodePage;

class CodePageTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndianUnicodeCodePageRecord()
    {
        $record = new CodePage($this->getLittleEndianPackFormatter());
        $this->assertEquals(hex2bin('42000200b004'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturndBigEndianUnicodeCodePageRecord()
    {
        $record = new CodePage($this->getBigEndianPackFormatter());
        $this->assertEquals(hex2bin('0042000204b0'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianUnicodeCodePageRecord()
    {
        $record = new CodePage($this->getMachineByteOrderEndianPackFormatter());
        $this->assertEquals(hex2bin('42000200b004'), $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordNumberException
     */
    public function shouldThrowInvalidRecordNumberExceptionWhenRecordNumberIsInvalid()
    {
        $record = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\Record\CodePage\CodePage', [
            'getRecordNumber'
        ]);
        
        $record->expects($this->any())
            ->method('getRecordNumber')
            ->willReturn('');
        
        $record->getRecord();
    }
}
