<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class DirTest extends TestCase
{
    public function testIsDir()
    {
        $this->assertEquals(false, $this->isDir(array()), 'Not File path');

        $this->assertTrue($this->isDir(__DIR__));

        $this->assertFalse($this->isDir('.file not found'), 'File not found');
    }
}