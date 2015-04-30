<?php
namespace TSwiackiewicz\SimpleExcelStreamWriter\Tests\ByteOrder;

use TSwiackiewicz\SimpleExcelStreamWriter\ByteOrder\ByteOrder;

class ByteOrderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getLittleEndian()
    {
        $byteOrder = new LittleEndianByteOrderMock();
        $this->assertEquals(ByteOrder::LITTLE_ENDIAN, $byteOrder->getEndian());
    }

    /**
     * @test
     */
    public function getBigEndian()
    {
        $byteOrder = new BigEndianByteOrderMock();
        $this->assertEquals(ByteOrder::BIG_ENDIAN, $byteOrder->getEndian());
    }

    /**
     * @test
     */
    public function getMachineByteOrder()
    {
        $byteOrder = new MachineByteOrderByteOrderMock();
        $this->assertEquals(ByteOrder::MACHINE_BYTE_ORDER, $byteOrder->getEndian());
    }
}
