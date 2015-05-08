<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests;

use TSwiackiewicz\ExcelStreamWriter\Tests\Record\NullRecord;
use TSwiackiewicz\ExcelStreamWriter\Tests\PackFormatter\NullPackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\NullByteOrder;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\BigEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\LittleEndianByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\MachineByteOrderByteOrderMock;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;

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
        $nullRecord = new NullRecord(new NullPackFormatter(new NullByteOrder()));
        
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

    protected function getPackFormatter()
    {
        return new PackFormatter(new NullByteOrder());
    }
    
    protected function getLittleEndianPackFormatter()
    {
        return new PackFormatter($this->getLittleEndianByteOrderMock());
    }

    protected function getBigEndianPackFormatter()
    {
        return new PackFormatter($this->getBigEndianByteOrderMock());
    }

    protected function getMachineByteOrderEndianPackFormatter()
    {
        return new PackFormatter($this->getMachineByteOrderEndianByteOrderMock());
    }

    protected function getLittleEndianByteOrderMock()
    {
        return new LittleEndianByteOrderMock();
    }

    protected function getBigEndianByteOrderMock()
    {
        return new BigEndianByteOrderMock();
    }

    protected function getMachineByteOrderEndianByteOrderMock()
    {
        return new MachineByteOrderByteOrderMock();
    }
}
