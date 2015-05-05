<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\PackFormatter;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

class PackFormatterTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function getFormatLittleEndianEmptyArgument()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertSame('', $formatter->getFormat([]));
    }

    /**
     * @test
     */
    public function getFormatBigEndianEmptyArgument()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertSame('', $formatter->getFormat([]));
    }

    /**
     * @test
     */
    public function getFormatMachineByteOrderEndianEmptyArgument()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertSame('', $formatter->getFormat([]));
    }

    /**
     * @test
     */
    public function getFormatLittleEndianShortArgument()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('v', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function getFormatBigEndianShortArgument()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('n', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function getFormatMachineByteOrderEndianShortArgument()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('S', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function getFormatLittleEndianLongArgument()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('V', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function getFormatBigEndianLongArgument()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('N', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function getFormatMachineByteOrderEndianLongArgument()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('L', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function getFormatLittleEndianDoubleArgument()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function getFormatBigEndianDoubleArgument()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function getFormatMachineByteOrderEndianDoubleArgument()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function getFormatLittleEndianCharArgument()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function getFormatBigEndianCharArgument()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function getFormatMachineByteOrderEndianCharArgument()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function getFormatLittleEndianMultipleArguments()
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
    public function getFormatBigEndianMultipleArguments()
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
    public function getFormatMachineByteOrderEndianMultipleArguments()
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
