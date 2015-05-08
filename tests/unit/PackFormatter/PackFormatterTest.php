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
    public function shouldReturnLittleEndianEmptyStringForEmptyArgument()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertSame('', $formatter->getFormat([]));
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianEmptyStringForEmptyArgument()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertSame('', $formatter->getFormat([]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianEmptyStringForEmptyArgument()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertSame('', $formatter->getFormat([]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianShortFormat()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('v', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianShortFormat()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('n', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianShortFormat()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('S', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianLongFormat()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('V', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianLongFormat()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('N', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianLongFormat()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('L', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianDoubleFormat()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianDoubleFormat()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianDoubleFormat()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianCharFormat()
    {
        $formatter = new PackFormatter(new LittleEndianByteOrderMock());
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnFormatBigEndianCharFormat()
    {
        $formatter = new PackFormatter(new BigEndianByteOrderMock());
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianCharFormat()
    {
        $formatter = new PackFormatter(new MachineByteOrderByteOrderMock());
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianMultipleFormat()
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
    public function shouldReturnBigEndianMultipleFormat()
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
    public function shouldReturnMachineByteOrderEndianMultipleFormat()
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
     * @test
     */
    public function shouldThrowInvalidArgumentExceptionWhenInvalidArgTypeIsSet()
    {
        $formatter = new PackFormatter(new ByteOrder());
        $this->assertEquals('C', $formatter->getFormat([
            'dummy'
        ]));
    }
}
