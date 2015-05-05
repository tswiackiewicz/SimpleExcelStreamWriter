<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Eof;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Record\Eof\Eof;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;

class EofTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function getRecordLittleEndian()
    {
        $record = new Eof(new PackFormatter(new LittleEndianByteOrderMock()));
        $this->assertEquals(hex2bin('0a000000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordBigEndian()
    {
        $record = new Eof(new PackFormatter(new BigEndianByteOrderMock()));
        $this->assertEquals(hex2bin('000a0000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordMachineByteOrderEndian()
    {
        $record = new Eof(new PackFormatter(new MachineByteOrderByteOrderMock()));
        $this->assertEquals(hex2bin('0a000000'), $record->getRecord());
    }

    /**
     * @test
     * @expectedException TSwiackiewicz\ExcelStreamWriter\Record\InvalidRecordNumberException
     */
    public function getRecordWithInvalidRecordNumber()
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
