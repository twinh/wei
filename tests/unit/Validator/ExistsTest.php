<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class ExistsTest extends TestCase
{
    public function testIsExists()
    {
        $this->assertFalse($this->isExists([]), 'Not File path');

        $this->assertTrue($this->isExists(__FILE__), 'File found');

        $this->assertFalse($this->isExists('.file not found'), 'File not found');
    }
}
