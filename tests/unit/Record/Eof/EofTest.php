<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Eof;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\Eof\Eof;

class EofTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndianEofRecord()
    {
        $record = new Eof($this->getLittleEndianPackFormatter());
        $this->assertEquals(hex2bin('0a000000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianEofRecord()
    {
        $record = new Eof($this->getBigEndianPackFormatter());
        $this->assertEquals(hex2bin('000a0000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianEofRecord()
    {
        $record = new Eof($this->getMachineByteOrderEndianPackFormatter());
        $this->assertEquals(hex2bin('0a000000'), $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordNumberException
     */
    public function shouldThrowInvalidRecordNumberExceptionWhenRecordNumberIsInvalid()
    {
        $record = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\Record\Eof\Eof', [
            'getRecordNumber'
        ]);
        
        $record->expects($this->any())
            ->method('getRecordNumber')
            ->willReturn('');
        
        $record->getRecord();
    }
}
