<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\PackFormatter;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;

class PackFormatterTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndianEmptyStringForEmptyArgument()
    {
        $formatter = $this->getLittleEndianPackFormatter();
        $this->assertSame('', $formatter->getFormat([]));
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianEmptyStringForEmptyArgument()
    {
        $formatter = $this->getBigEndianPackFormatter();
        $this->assertSame('', $formatter->getFormat([]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianEmptyStringForEmptyArgument()
    {
        $formatter = $this->getMachineByteOrderEndianPackFormatter();
        $this->assertSame('', $formatter->getFormat([]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianShortFormat()
    {
        $formatter = $this->getLittleEndianPackFormatter();
        $this->assertEquals('v', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianShortFormat()
    {
        $formatter = $this->getBigEndianPackFormatter();
        $this->assertEquals('n', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianShortFormat()
    {
        $formatter = $this->getMachineByteOrderEndianPackFormatter();
        $this->assertEquals('S', $formatter->getFormat([
            PackFormatter::SHORT
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianLongFormat()
    {
        $formatter = $this->getLittleEndianPackFormatter();
        $this->assertEquals('V', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianLongFormat()
    {
        $formatter = $this->getBigEndianPackFormatter();
        $this->assertEquals('N', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianLongFormat()
    {
        $formatter = $this->getMachineByteOrderEndianPackFormatter();
        $this->assertEquals('L', $formatter->getFormat([
            PackFormatter::LONG
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianDoubleFormat()
    {
        $formatter = $this->getLittleEndianPackFormatter();
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnBigEndianDoubleFormat()
    {
        $formatter = $this->getBigEndianPackFormatter();
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianDoubleFormat()
    {
        $formatter = $this->getMachineByteOrderEndianPackFormatter();
        $this->assertEquals('d', $formatter->getFormat([
            PackFormatter::DOUBLE
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianCharFormat()
    {
        $formatter = $this->getLittleEndianPackFormatter();
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnFormatBigEndianCharFormat()
    {
        $formatter = $this->getBigEndianPackFormatter();
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndianCharFormat()
    {
        $formatter = $this->getMachineByteOrderEndianPackFormatter();
        $this->assertEquals('C', $formatter->getFormat([
            PackFormatter::CHAR
        ]));
    }

    /**
     * @test
     */
    public function shouldReturnLittleEndianMultipleFormat()
    {
        $formatter = $this->getLittleEndianPackFormatter();
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
        $formatter = $this->getBigEndianPackFormatter();
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
        $formatter = $this->getMachineByteOrderEndianPackFormatter();
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
    public function shouldThrowInvalidArgumentExceptionWhenArgTypeIsInvalid()
    {
        $formatter = $this->getMachineByteOrderEndianPackFormatter();
        $this->assertEquals('C', $formatter->getFormat([
            'dummy'
        ]));
    }
}
