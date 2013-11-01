<?php

namespace WeiTest\Validator;

class ExistsTest extends TestCase
{
    public function testIsExists()
    {
        $this->assertFalse($this->isExists(array()), 'Not File path');

        $this->assertTrue($this->isExists(__FILE__), 'File found');

        $this->assertFalse($this->isExists('.file not found'), 'File not found');
    }
}
