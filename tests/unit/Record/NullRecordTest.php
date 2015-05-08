<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests\Record;

use TSwiackiewicz\ExcelStreamWriter\Tests\AbstractTestCase;

class NullRecordTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function shouldReturnEmptyStringRecord()
    {
        $record = new NullRecord($this->getMachineByteOrderEndianPackFormatter());
        $this->assertSame('', $record->getRecord());
    }
}
