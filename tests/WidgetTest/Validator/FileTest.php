<?php

namespace WidgetTest\Validator;

class FileTest extends TestCase
{
    public function createFileValidator()
    {
        return new \Widget\Validator\File(array(
            'widget' => $this->widget,
        ));
    }

    public function testIsFile()
    {
        $this->assertFalse($this->is('file', array()), 'Not File path');

        $this->assertTrue($this->is('file', __FILE__), 'File found');

        $this->assertFalse($this->is('file', '.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (is_file($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->is('file', $file), 'File in include path found');
                break;
            }
        }
    }

    public function testNotFound()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file('/not_found_this_file'));
    }

    public function testExts()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file(__FILE__, array(
            'exts' => 'gif,jpg',
            'excludeExts' => array('doc', 'php')
        )));

        $this->assertEquals(array('excludeExts', 'exts'), array_keys($file->getErrors()));
    }

    public function testSize()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file(__FILE__, array(
            'maxSize' => 8, // 8bytes
            'minSize' => '10.5MB'
        )));

        $this->assertEquals(array('maxSize', 'minSize'), array_keys($file->getErrors()));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnexpectedExts()
    {
        $file = $this->createFileValidator();

        $file->setExcludeExts(new \stdClass());
    }

    public function testMimeType()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file(dirname(__DIR__) . '/Fixtures/5x5.gif', array(
            'mimeTypes' => array(
                'image/jpg'
            ),
            'excludeMimeTypes' => array(
                'image/gif'
            )
        )));

        $this->assertEquals(array('mimeTypes', 'excludeMimeTypes'), array_keys($file->getErrors()));
    }

    public function testMimeTypeWildcard()
    {
        $file = $this->createFileValidator();

        $this->assertTrue($file(dirname(__DIR__) . '/Fixtures/5x5.gif', array(
            'mimeTypes' => array(
                'image/*'
            )
        )));
    }

    public function testStringAsParameter()
    {
        $file = $this->createFileValidator();

        $this->assertTrue($file(dirname(__DIR__) . '/Fixtures/5x5.gif', array(
            'mimeTypes' => 'image/jpg,image/gif'
        )));
    }

    public function testSplFileInfoAsInput()
    {
        $file = $this->createFileValidator();

        $this->assertTrue($file(new \SplFileInfo(dirname(__DIR__) . '/Fixtures/5x5.gif')));
    }

    public function testFilesArrayAsInput()
    {
        $file = $this->createFileValidator();

        $input = dirname(__DIR__) . '/Fixtures/5x5.gif';
        $array = array(
            'tmp_name' => $input,
            'name' => $input
        );

        $this->assertTrue($file($array));
    }

    public function testInvalidArrayAsInput()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file(array()));
    }

    public function testFileWithoutExtension()
    {
        $file = dirname(__DIR__) . '/Fixtures/5x5';
        $this->assertTrue($this->isFile($file));
        $this->assertEquals('', $this->isFile->getExt());
    }

    public function testRelativeFileWithoutExtension()
    {
        $file = __DIR__ . '/../Fixtures/5x5';
        $this->assertTrue($this->isFile($file));
        $this->assertEquals('', $this->isFile->getExt());
    }
}