<?php
namespace TSwiackiewicz\SimpleExcelStreamWriter\Tests\ByteOrder;

use TSwiackiewicz\SimpleExcelStreamWriter\ByteOrder\ByteOrder;

class ByteOrderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getEndianLittleEndian()
    {
        $byteOrder = new LittleEndianByteOrderMock();
        $this->assertEquals(ByteOrder::LITTLE_ENDIAN, $byteOrder->getEndian());
    }

    /**
     * @test
     */
    public function getEndianBigEndian()
    {
        $byteOrder = new BigEndianByteOrderMock();
        $this->assertEquals(ByteOrder::BIG_ENDIAN, $byteOrder->getEndian());
    }

    /**
     * @test
     */
    public function getEndianMachineByteOrder()
    {
        $byteOrder = new MachineByteOrderByteOrderMock();
        $this->assertEquals(ByteOrder::MACHINE_BYTE_ORDER, $byteOrder->getEndian());
    }
}
