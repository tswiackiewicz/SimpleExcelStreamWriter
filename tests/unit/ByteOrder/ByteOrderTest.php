<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

class ByteOrderTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnLittleEndian()
    {
        $byteOrder = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder', [
            'getMachineByteOrderValue',
            'getLittleEndianValue',
            'getBigEndianValue'
        ]);
        
        $byteOrder->expects($this->any())
            ->method('getMachineByteOrderValue')
            ->willReturn(0x6162797A);
        $byteOrder->expects($this->any())
            ->method('getLittleEndianValue')
            ->willReturn(0x6162797A);
        $byteOrder->expects($this->any())
            ->method('getBigEndianValue')
            ->willReturn(0x7A797961);
        
        $this->assertEquals(ByteOrder::LITTLE_ENDIAN, $byteOrder->getEndian());
    }

    /**
     * @test
     */
    public function shouldReturnBigEndian()
    {
        $byteOrder = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder', [
            'getMachineByteOrderValue',
            'getLittleEndianValue',
            'getBigEndianValue'
        ]);
        
        $byteOrder->expects($this->any())
            ->method('getMachineByteOrderValue')
            ->willReturn(0x7A797961);
        $byteOrder->expects($this->any())
            ->method('getLittleEndianValue')
            ->willReturn(0x6162797A);
        $byteOrder->expects($this->any())
            ->method('getBigEndianValue')
            ->willReturn(0x7A797961);
        
        $this->assertEquals(ByteOrder::BIG_ENDIAN, $byteOrder->getEndian());
    }

    /**
     * @test
     */
    public function shouldReturnMachineByteOrderEndian()
    {
        $byteOrder = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder', [
            'getMachineByteOrderValue',
            'getLittleEndianValue',
            'getBigEndianValue'
        ]);
        
        $byteOrder->expects($this->any())
            ->method('getMachineByteOrderValue')
            ->willReturn(0x6162797A);
        $byteOrder->expects($this->any())
            ->method('getLittleEndianValue')
            ->willReturn(0x7A797961);
        $byteOrder->expects($this->any())
            ->method('getBigEndianValue')
            ->willReturn(0x7A797961);
        
        $this->assertEquals(ByteOrder::MACHINE_BYTE_ORDER, $byteOrder->getEndian());
    }
}
