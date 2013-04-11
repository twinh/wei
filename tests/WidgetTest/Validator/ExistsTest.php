<?php

namespace WidgetTest\Validator;

class ExistsTest extends TestCase
{
    public function testIsExists()
    {
        $this->assertFalse($this->isExists(array()), 'Not File path');

        $this->assertTrue($this->isExists(__FILE__), 'File found');

        $this->assertFalse($this->isExists('.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (file_exists($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->isExists($file), 'File in include path found');
                break;
            }
        }
    }
}