<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder;

use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;

/**
 * Pusta klasa do wykrywania ustawionego trybu endian srodowiska
 */
class NullByteOrder extends ByteOrder
{

    /**
     * Wykrywanie trybu endian (Little-Endian, Big-Endian, Machine Byte Order)
     * 
     * @return string tryb endian
     */
    public function getEndian()
    {
        return ByteOrder::MACHINE_BYTE_ORDER;
    }
}
