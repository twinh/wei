<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsExistsTest extends BaseValidatorTestCase
{
    public function testIsExists()
    {
        $this->assertFalse($this->isExists([]), 'Not File path');

        $this->assertTrue($this->isExists(__FILE__), 'File found');

        $this->assertFalse($this->isExists('.file not found'), 'File not found');
    }
}
