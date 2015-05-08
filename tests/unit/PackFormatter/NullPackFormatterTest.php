<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\PackFormatter;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\NullByteOrder;

class NullPackFormatterTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnEmptyString()
    {
        $formatter = new NullPackFormatter(new NullByteOrder());
        $this->assertSame('', $formatter->getFormat([]));
    }
}
