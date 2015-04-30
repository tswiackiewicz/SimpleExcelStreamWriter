<?php
namespace TSwiackiewicz\SimpleExcelStreamWriter\Tests\PackFormatter;

use TSwiackiewicz\SimpleExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\SimpleExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\SimpleExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\SimpleExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;
use TSwiackiewicz\SimpleExcelStreamWriter\ByteOrder\ByteOrder;

class PackFormatterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getFormatShortArgLittleEndian()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('v', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function getFormatShortArgBigEndian()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('n', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function getFormatShortArgMachineByteOrderEndian()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('S', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function getFormatLongArgLittleEndian()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('V', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function getFormatLongArgBigEndian()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('N', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function getFormatLongArgMachineByteOrderEndian()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('L', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function getFormatDoubleArgLittleEndian()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function getFormatDoubleArgBigEndian()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function getFormatDoubleArgMachineByteOrderEndian()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function getFormatCharArgLittleEndian()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function getFormatCharArgBigEndian()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function getFormatCharArgMachineByteOrderEndian()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('SLdC', $formatter->getFormat([
            PackFormatter::SHORT,
            PackFormatter::LONG,
            PackFormatter::DOUBLE,
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function getFormatMultipleArgLittleEndian()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('vVdC', $formatter->getFormat([
            PackFormatter::SHORT,
            PackFormatter::LONG,
            PackFormatter::DOUBLE,
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function getFormatMultipleArgBigEndian()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('nNdC', $formatter->getFormat([
            PackFormatter::SHORT,
            PackFormatter::LONG,
            PackFormatter::DOUBLE,
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function getFormatMultipleArgMachineByteOrderEndian()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported format arg!
     * @test
     */
    public function getFormatInvalidArgType()
    {
        $formatter = new PackFormatter(new ByteOrder());
        $this->assertEquals('C', $formatter->getFormat([
            'dummy'
        ]));
    }
}
