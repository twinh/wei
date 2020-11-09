<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsDirTest extends BaseValidatorTestCase
{
    public function testIsDir()
    {
        //$this->assertEquals(false, $this->isDir(array()), 'Not File path');

        $this->assertTrue($this->isDir(__DIR__));

        $this->assertFalse($this->isDir('.file not found'), 'File not found');
    }
}
