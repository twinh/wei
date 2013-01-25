<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class FileTest extends TestCase
{
    public function testIsFile()
    {
        $this->assertFalse(false, $this->isFile(array()), 'Not File path');

        $this->assertEquals($this->isFile(__FILE__), __FILE__, 'File found');

        $this->assertFalse($this->isFile('.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (is_file($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->isFile($file), 'File in include path found');
                break;
            }
        }
    }
}