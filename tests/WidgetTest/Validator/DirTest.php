<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class DirTest extends TestCase
{
    public function testIsDir()
    {
        $this->assertEquals(false, $this->isDir(array()), 'Not File path');

        $this->assertEquals($this->isDir(__DIR__), __DIR__, 'File found');

        $this->assertFalse($this->isDir('.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->isDir($file), 'File in include path found');
                break;
            }
        }
    }
}