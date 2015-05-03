<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record\CodePage;

use TSwiackiewicz\ExcelStreamWriter\Record\CodePage\CodePage;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;

class CodePageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getRecordUnicodePageLittleEndian()
    {
        $record = new CodePage(new PackFormatter(new LittleEndianByteOrderMock()));
        $this->assertEquals(hex2bin('42000200b004'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordUnicodePageBigEndian()
    {
        $record = new CodePage(new PackFormatter(new BigEndianByteOrderMock()));
        $this->assertEquals(hex2bin('0042000204b0'), $record->getRecord());
    }

    /**
     * @test
     */
    public function getRecordUnicodePageMachineByteOrderEndian()
    {
        $record = new CodePage(new PackFormatter(new MachineByteOrderByteOrderMock()));
        $this->assertEquals(hex2bin('42000200b004'), $record->getRecord());
    }
}
