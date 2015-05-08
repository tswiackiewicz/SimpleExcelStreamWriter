<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;
use TSwiackiewicz\ExcelStreamWriter\Tests\PackFormatter\NullPackFormatter;
use TSwiackiewicz\ExcelStreamWriter\Tests\ByteOrder\NullByteOrder;

class NullRecordTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnEmptyStringRecord()
    {
        $record = new NullRecord(new NullPackFormatter(new NullByteOrder()));
        $this->assertSame('', $record->getRecord());
    }
}
