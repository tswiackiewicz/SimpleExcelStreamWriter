<?php
namespace TSwiackiewicz\ExcelStreamWriter\Tests;

use TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter;
use TSwiackiewicz\ExcelStreamWriter\Record\RecordFactory;
use TSwiackiewicz\ExcelStreamWriter\Tests\Record\NullRecord;
use TSwiackiewicz\ExcelStreamWriter\PackFormatter\PackFormatter;
use TSwiackiewicz\ExcelStreamWriter\ByteOrder\ByteOrder;
use TSwiackiewicz\ExcelStreamWriter\Record\Record;

class ExcelStreamWriterTest extends AbstractTestCase
{

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionWhenPathIsEmpty()
    {
        $writer = new ExcelStreamWriter('');
        $writer->open();
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function shouldThrowExceptionWhenUnableToOpenFile()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'fopen'
        ]);
        $writer->setPath(__DIR__);
        
        $writer->expects($this->exactly(1))
            ->method('fopen')
            ->with(__DIR__, 'w+b')
            ->willReturn(false);
        
        $writer->open();
    }

    /**
     * @test
     */
    public function shouldOpenFileSuccessfully()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'fopen',
            'writeRecord',
            'close'
        ]);
        $writer->setPath(__DIR__);
        $writer->setFactory($this->getRecordFactory());
        
        $writer->expects($this->exactly(1))
            ->method('fopen')
            ->with(__DIR__, 'w+b')
            ->willReturn(true);
        
        $writer->expects($this->exactly(1))
            ->method('writeRecord')
            ->willReturn(true);
        
        $writer->expects($this->any())
            ->method('close')
            ->willReturn(true);
        
        $writer->open();
        $this->assertAttributeEquals(true, 'writerOpened', $writer);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function shouldThrowExceptionWhenUnableToCloseFile()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'writeRecord',
            'fclose'
        ]);
        $writer->setPath(__DIR__);
        $writer->setFactory($this->getRecordFactory());
        
        $writer->expects($this->exactly(1))
            ->method('writeRecord')
            ->willReturn(true);
        
        $writer->expects($this->exactly(1))
            ->method('fclose')
            ->willReturn(false);
        
        $writer->close();
    }

    /**
     * @test
     */
    public function shouldCloseFileSuccessfully()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'writeRecord',
            'fclose'
        ]);
        $writer->setPath(__DIR__);
        $writer->setFactory($this->getRecordFactory());
        
        $writer->expects($this->exactly(1))
            ->method('writeRecord')
            ->willReturn(true);
        
        $writer->expects($this->exactly(1))
            ->method('fclose')
            ->willReturn(true);
        
        $writer->close();
        $this->assertAttributeEquals(true, 'writerClosed', $writer);
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenUnableToAddNextRow()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'addRow'
        ]);
        
        $writer->expects($this->exactly(1))
            ->method('addRow')
            ->willReturn(false);
        
        $this->assertFalse($writer->addNextRow([
            '1',
            'Subject',
            'Content'
        ]));
    }

    /**
     * @test
     */
    public function shouldAddNextRowSuccessfully()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'addRow'
        ]);
        
        $writer->expects($this->exactly(1))
            ->method('addRow')
            ->with(0, [
            '1',
            'Subject',
            'Content'
        ])
            ->willReturn(true);
        
        $this->assertTrue($writer->addNextRow([
            '1',
            'Subject',
            'Content'
        ]));
        $this->assertAttributeEquals(1, 'rowsCount', $writer);
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenUnableToAddCell()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'addCell'
        ]);
        
        $writer->expects($this->exactly(1))
            ->method('addCell')
            ->with(0, 0, '1')
            ->willReturn(false);
        
        $this->assertFalse($writer->addRow(0, [
            '1',
            'Subject',
            'Content'
        ]));
    }

    /**
     * @test
     */
    public function shouldAddRowSuccessfully()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'addCell'
        ]);
        
        $writer->expects($this->at(0))
            ->method('addCell')
            ->with(0, 0, '1')
            ->willReturn(true);
        
        $writer->expects($this->at(1))
            ->method('addCell')
            ->with(0, 1, 'Subject')
            ->willReturn(true);
        
        $writer->expects($this->at(2))
            ->method('addCell')
            ->with(0, 2, 'Content')
            ->willReturn(true);
        
        $this->assertTrue($writer->addRow(0, [
            '1',
            'Subject',
            'Content'
        ]));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function shouldThrowInvalidArgumentExceptionWhenOpenThrowsInvalidArgumentException()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'open'
        ]);
        
        $writer->expects($this->exactly(1))
            ->method('open')
            ->willThrowException(new \InvalidArgumentException());
        
        $writer->addCell(0, 0, 0);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function shouldThrowExceptionWhenOpenThrowsException()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'open'
        ]);
        
        $writer->expects($this->exactly(1))
            ->method('open')
            ->willThrowException(new \Exception());
        
        $writer->addCell(0, 0, 0);
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenUnableToWriteNumberCell()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'open',
            'writeRecord'
        ]);
        $writer->setFactory($this->getRecordFactory());
        
        $writer->expects($this->exactly(1))
            ->method('open')
            ->willReturn(true);
        
        $writer->expects($this->exactly(1))
            ->method('writeRecord')
            ->willReturn(false);
        
        $this->assertFalse($writer->addCell(0, 0, 99.99));
    }

    /**
     * @test
     */
    public function shouldAddNumberCellSuccessfully()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'open',
            'writeRecord'
        ]);
        $writer->setFactory($this->getRecordFactory());
        
        $writer->expects($this->exactly(1))
            ->method('open')
            ->willReturn(true);
        
        $writer->expects($this->exactly(1))
            ->method('writeRecord')
            ->willReturn(true);
        
        $this->assertTrue($writer->addCell(0, 0, 99.99));
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenUnableToWriteStringCell()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'open',
            'writeRecord'
        ]);
        $writer->setFactory($this->getRecordFactory());
        
        $writer->expects($this->exactly(1))
            ->method('open')
            ->willReturn(true);
        
        $writer->expects($this->exactly(1))
            ->method('writeRecord')
            ->willReturn(false);
        
        $this->assertFalse($writer->addCell(0, 0, 'test string'));
    }

    /**
     * @test
     */
    public function shouldAddStringCellSuccessfully()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'open',
            'writeRecord'
        ]);
        $writer->setFactory($this->getRecordFactory());
        
        $writer->expects($this->exactly(1))
            ->method('open')
            ->willReturn(true);
        
        $writer->expects($this->exactly(1))
            ->method('writeRecord')
            ->willReturn(true);
        
        $this->assertTrue($writer->addCell(0, 0, 'test string'));
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenUnableToWriteBlankCell()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'open',
            'writeRecord'
        ]);
        $writer->setFactory($this->getRecordFactory());
        
        $writer->expects($this->exactly(1))
            ->method('open')
            ->willReturn(true);
        
        $writer->expects($this->exactly(1))
            ->method('writeRecord')
            ->willReturn(false);
        
        $this->assertFalse($writer->addCell(0, 0, ''));
    }

    /**
     * @test
     */
    public function shouldAddBlankCellSuccessfully()
    {
        $writer = $this->getMockWithoutConstructingWithMethods('TSwiackiewicz\ExcelStreamWriter\ExcelStreamWriter', [
            'open',
            'writeRecord'
        ]);
        $writer->setFactory($this->getRecordFactory());
        
        $writer->expects($this->exactly(1))
            ->method('open')
            ->willReturn(true);
        
        $writer->expects($this->exactly(1))
            ->method('writeRecord')
            ->willReturn(true);
        
        $this->assertTrue($writer->addCell(0, 0, ''));
    }
}
