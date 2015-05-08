<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests;

use TSwiackiewicz\ExcelStreamWriter\Tests\Record\NullRecord;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Pomocnicza metoda zwracajaca mocka bez domyslnego konstruktora
     * 
     * @param string $name
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockWithoutConstructing($name)
    {
        return $this->getMockBuilder($name)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Pomocnicza metoda zwracajaca mocka bez domyslnego konstruktora dla wybranych metod
     * 
     * @param string $name
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockWithoutConstructingWithMethods($name, array $methods)
    {
        return $this->getMockBuilder($name)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Pomocnicza metoda zwracajaca mocka ExcelStreamWriter
     * 
     * @param string $path sciezka do pliku wynikowego
     * @param array $methods lista metod, ktore zostana zamockowane
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getExcelStreamWriter($path, array $methods = [])
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', $methods);
        $writer->setPath(__DIR__);
        $writer->setFactory($this->getRecordFactory());
        
        return $writer;
    }

    /**
     * Pomocnicza metoda zwracajaca mocka RecordFactory
     * Wszystkie metody RecordFactory zwracaja puste obiekty NullRecord
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRecordFactory()
    {
        $nullRecord = new NullRecord($this->getMachineByteOrderEndianPackFormatter());
        
        $factory = $this->getMockWithoutConstructing('TSwiackiewicz\ExcelStreamWriter\Record\RecordFactory');
        
        $factory->expects($this->any())
            ->method('getBof')
            ->willReturn($nullRecord);
        
        $factory->expects($this->any())
            ->method('getEof')
            ->willReturn($nullRecord);
        
        $factory->expects($this->any())
            ->method('getBlankCell')
            ->willReturn($nullRecord);
        
        $factory->expects($this->any())
            ->method('getNumberCell')
            ->willReturn($nullRecord);
        
        $factory->expects($this->any())
            ->method('getStringCell')
            ->willReturn($nullRecord);
        
        return $factory;
    }

    /**
     * Pomocnicza metoda zwracajaca PackFormattera dla trybu endian Little Endian
     * 
     * @return PackFormatter
     */
    protected function getLittleEndianPackFormatter()
    {
        return new PackFormatter($this->getLittleEndianByteOrderMock());
    }

    /**
     * Pomocnicza metoda zwracajaca PackFormattera dla trybu endian Big Endian
     * 
     * @return PackFormatter
     */
    protected function getBigEndianPackFormatter()
    {
        return new PackFormatter($this->getBigEndianByteOrderMock());
    }

    /**
     * Pomocnicza metoda zwracajaca PackFormattera dla trybu endian Machine Byte Order
     * 
     * @return PackFormatter
     */
    protected function getMachineByteOrderEndianPackFormatter()
    {
        return new PackFormatter($this->getMachineByteOrderEndianByteOrderMock());
    }

    /**
     * Pomocnicza metoda zwracajaca mocka ByteOrder dla trybu endian Little Endian
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getLittleEndianByteOrderMock()
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
        
        return $byteOrder;
    }

    /**
     * Pomocnicza metoda zwracajaca mocka ByteOrder dla trybu endian Big Endian
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBigEndianByteOrderMock()
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
        
        return $byteOrder;
    }

    /**
     * Pomocnicza metoda zwracajaca mocka ByteOrder dla trybu endian Machine Byte Order
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMachineByteOrderEndianByteOrderMock()
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
        
        return $byteOrder;
    }
}
