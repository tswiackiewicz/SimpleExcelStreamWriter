<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Bof;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\Bof\Bof;

class BofTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndianBofRecord()
    {
        $record = new Bof($this->getLittleEndianPackFormatter());
        $this->assertEquals(hex2bin('0908100000061000bb0dcc07d100010006040000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianBofRecord()
    {
        $record = new Bof($this->getBigEndianPackFormatter());
        $this->assertEquals(hex2bin('08090010060000100dbb07cc000100d100000406'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianBofRecord()
    {
        $record = new Bof($this->getMachineByteOrderEndianPackFormatter());
        $this->assertEquals(hex2bin('0908100000061000bb0dcc07d100010006040000'), $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordNumberException
     */
    public function shouldThrowInvalidRecordNumberExceptionWhenRecordNumberIsInvalid()
    {
        $record = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\Record\Bof\Bof', [
            'getRecordNumber'
        ]);
        
        $record->expects($this->any())
            ->method('getRecordNumber')
            ->willReturn('');
        
        $record->getRecord();
    }
}
