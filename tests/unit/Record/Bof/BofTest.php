<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\Bof;

use TSwiackiewicz\ExcelStreamWriter\Record\Bof\Bof;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;

class BofTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getRecordLittleEndian()
    {
        $record = new Bof(new PackFormatter(new LittleEndianByteOrderMock()));
        $this->assertEquals(hex2bin('0908100000061000bb0dcc07d100010006040000'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordBigEndian()
    {
        $record = new Bof(new PackFormatter(new BigEndianByteOrderMock()));
        $this->assertEquals(hex2bin('08090010060000100dbb07cc000100d100000406'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordMachineByteOrderEndian()
    {
        $record = new Bof(new PackFormatter(new MachineByteOrderByteOrderMock()));
        $this->assertEquals(hex2bin('0908100000061000bb0dcc07d100010006040000'), $record->getRecord());
    }
}
