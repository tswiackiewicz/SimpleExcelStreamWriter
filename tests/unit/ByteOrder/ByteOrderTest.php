<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder;

use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;
use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;

class ByteOrderTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndian()
    {
        $byteOrder = new LittleEndianByteOrderMock();
        $this->assertEquals(ByteOrder::LITTLE_ENDIAN, $byteOrder->getEndian());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndian()
    {
        $byteOrder = new BigEndianByteOrderMock();
        $this->assertEquals(ByteOrder::BIG_ENDIAN, $byteOrder->getEndian());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndian()
    {
        $byteOrder = new MachineByteOrderByteOrderMock();
        $this->assertEquals(ByteOrder::MACHINE_BYTE_ORDER, $byteOrder->getEndian());
    }
}
