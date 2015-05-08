<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\CodePage;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\CodePage\CodePage;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;

class CodePageTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndianUnicodeCodePageRecord()
    {
        $record = new CodePage(new PackFormatter(new LittleEndianByteOrderMock()));
        $this->assertEquals(hex2bin('42000200b004'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturndBigEndianUnicodeCodePageRecord()
    {
        $record = new CodePage(new PackFormatter(new BigEndianByteOrderMock()));
        $this->assertEquals(hex2bin('0042000204b0'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianUnicodeCodePageRecord()
    {
        $record = new CodePage(new PackFormatter(new MachineByteOrderByteOrderMock()));
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
