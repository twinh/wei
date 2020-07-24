<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class FileTest extends TestCase
{
    public function createFileValidator()
    {
        return new \Wei\Validator\File([
            'wei' => $this->wei,
        ]);
    }

    public function testIsFile1()
    {
        $this->assertFalse($this->isFile([]), 'Not File path');
    }

    public function testIsFile2()
    {
        $this->assertTrue($this->isFile(__FILE__), 'File found');
    }

    public function testIsFile3()
    {
        $this->assertFalse($this->isFile('.file not found'), 'File not found');
    }

    public function testNotFound()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file('/not_found_this_file'));
    }

    public function testExts()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file(__FILE__, [
            'exts' => 'gif,jpg',
            'excludeExts' => ['doc', 'php'],
        ]));

        $this->assertEquals(['excludeExts', 'exts'], array_keys($file->getErrors()));
    }

    public function testSize()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file(__FILE__, [
            'maxSize' => 8, // 8bytes
            'minSize' => '10.5MB',
        ]));

        $this->assertEquals(['maxSize', 'minSize'], array_keys($file->getErrors()));
    }

    public function testUnexpectedExts()
    {
        $this->expectException(\InvalidArgumentException::class);

        $file = $this->createFileValidator();

        $file->setExcludeExts(new \stdClass());
    }

    public function testMimeType()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file(dirname(__DIR__) . '/Fixtures/5x5.gif', [
            'mimeTypes' => [
                'image/jpg',
            ],
            'excludeMimeTypes' => [
                'image/gif',
            ],
        ]));

        $this->assertEquals(['mimeTypes', 'excludeMimeTypes'], array_keys($file->getErrors()));
    }

    public function testMimeTypeWildcard()
    {
        $file = $this->createFileValidator();

        $this->assertTrue($file(dirname(__DIR__) . '/Fixtures/5x5.gif', [
            'mimeTypes' => [
                'image/*',
            ],
        ]));
    }

    public function testStringAsParameter()
    {
        $file = $this->createFileValidator();

        $this->assertTrue($file(dirname(__DIR__) . '/Fixtures/5x5.gif', [
            'mimeTypes' => 'image/jpg,image/gif',
        ]));
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
        $array = [
            'tmp_name' => $input,
            'name' => $input,
        ];

        $this->assertTrue($file($array));
    }

    public function testInvalidArrayAsInput()
    {
        $file = $this->createFileValidator();

        $this->assertFalse($file([]));
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
