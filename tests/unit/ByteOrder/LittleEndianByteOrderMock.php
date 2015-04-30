<?php
namespace TSwiackiewicz\SimpleExcelStreamWriter\Tests\ByteOrder;

use TSwiackiewicz\SimpleExcelStreamWriter\ByteOrder\ByteOrder;

class LittleEndianByteOrderMock extends ByteOrder
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
        return 0x6162797A;
    }

    /**
     * Pobranie testowej wartosci w formacie big endian
     * 
     * @return integer binarna postac testowej wartosci w formacie big endian
     */
    protected function getBigEndianValue()
    {
        return 0x7A797961;
    }
}