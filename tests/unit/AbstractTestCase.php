<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests;

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
}
