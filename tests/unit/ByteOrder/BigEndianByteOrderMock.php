<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder;

use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

class BigEndianByteOrderMock extends ByteOrder
{

    /**
     * Pobranie testowej wartosci w formacie machine byte order
     * 
     * @return integer binarna postac testowej wartosci w formacie machine byte order
     */
    protected function getMachineByteOrderValue()
    {
        return 0x6162797A;
    }

    /**
     * Pobranie testowej wartosci w formacie little endian
     * 
     * @return integer binarna postac testowej wartosci w formacie little endian
     */
    protected function getLittleEndianValue()
    {
        return 0x7A796261;
    }

    /**
     * Pobranie testowej wartosci w formacie big endian
     * 
     * @return integer binarna postac testowej wartosci w formacie big endian
     */
    protected function getBigEndianValue()
    {
        return 0x6162797A;
    }
}