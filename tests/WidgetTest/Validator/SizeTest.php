<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class SizeTest extends TestCase
{
    public function testSize()
    {
        $file = __FILE__;
        $size = filesize($file);

        $this->assertTrue($this->isSize($file, 1, $size));
        $this->assertFalse($this->isSize($file, 1, $size - 1));
    }
}