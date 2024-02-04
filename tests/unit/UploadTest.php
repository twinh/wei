<?php

namespace WeiTest;

/**
 * @internal
 */
final class UploadTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        $dir = 'uploads';
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) {
                continue;
            }
            unlink($dir . '/' . $file);
        }
        rmdir($dir);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->upload->setOption('unitTest', true);
    }

    /**
     * @dataProvider providerForUploadError
     * @param mixed $error
     * @param mixed $name
     */
    public function testUploadError($error, $name)
    {
        $this->req->setOption('files', [
            'upload' => [
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => '',
                'size' => 20,
                'error' => $error,
            ],
        ]);

        $this->upload();

        $this->assertTrue($this->upload->hasError($name));
    }

    public static function providerForUploadError()
    {
        return [
            [
                \UPLOAD_ERR_INI_SIZE, 'maxSize',
            ],
            [
                \UPLOAD_ERR_FORM_SIZE, 'formLimit',
            ],
            [
                \UPLOAD_ERR_PARTIAL, 'partial',
            ],
            [
                \UPLOAD_ERR_NO_FILE, 'noFile',
            ],
            [
                \UPLOAD_ERR_NO_TMP_DIR, 'noTmpDir',
            ],
            [
                \UPLOAD_ERR_CANT_WRITE, 'cantWrite',
            ],
            [
                \UPLOAD_ERR_EXTENSION, 'extension',
            ],
            [
                'noThisError', 'noFile',
            ],
        ];
    }

    public function testUploadBySpecifiedName()
    {
        $this->req->setOption('files', [
            'upload' => [
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => '',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
            'picture2' => [
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => '',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
        ]);

        $this->upload('picture2');
        $this->assertEquals('picture2', $this->upload->getOption('field'));

        $this->upload('notThisFiled');
        $this->assertTrue($this->upload->hasError('noFile'));
    }

    public function testInvoker()
    {
        $this->upload([
            'field' => 'upload',
        ]);

        $this->assertEquals('upload', $this->upload->getOption('field'));
    }

    public function testUploadImage()
    {
        $this->req->setOption('files', [
            'picture' => [
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
        ]);

        $this->upload([
            'maxWidth' => 3,
        ]);

        $this->assertTrue($this->upload->hasError('widthTooBig'));
    }

    public function testUploadNormalFile()
    {
        $this->req->setOption('files', [
            'picture' => [
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
        ]);

        $this->upload([
            'exts' => 'jpg,png',
        ]);

        $this->assertTrue($this->upload->hasError('exts'));
    }

    public function testUploadFileLargerThanMaxPostSize()
    {
        $this->req->setOption('files', []);
        $this->req->setOption('posts', []);

        $this->upload([
            'field' => 'bigFile',
        ]);

        $this->assertTrue($this->upload->hasError('postSize'));
    }

    public function testSaveFile()
    {
        $this->req->setOption('files', [
            'picture' => [
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
        ]);

        $this->upload([
            'dir' => 'uploads',
        ]);

        $this->assertFileExists($this->upload->getFile());
    }

    public function testUploadWithCustomName()
    {
        $this->req->setOption('files', [
            'picture' => [
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
        ]);

        $this->upload([
            'dir' => 'uploads',
            'fileName' => 'custom',
        ]);

        $file = 'uploads/custom.gif';
        $this->assertEquals($file, $this->upload->getFile());
        $this->assertFileExists($file);
    }

    public function testSaveFileToCustomDir()
    {
        $this->req->setOption('files', [
            'picture' => [
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
        ]);

        $dir = 'uploads/' . date('Ymd');
        $result = $this->upload([
            'dir' => $dir,
        ]);

        $file = $this->upload->getFile();

        $this->assertTrue($result);
        $this->assertFileExists($dir . '/test.gif');

        unlink($file);
        rmdir(dirname($file));
    }

    public function testGetSetDir()
    {
        $this->upload->setDir('uploads/');

        $this->assertEquals('uploads', $this->upload->getDir());
    }

    /**
     * For code coverage only, not the recommend way to write your logic
     */
    public function testCantMoveFile()
    {
        $this->req->setOption('files', [
            'picture' => [
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
        ]);

        // Avoid Warning: copy(uploads/cus/tom.gif) [function.copy]: failed to open stream: No such file or directory
        // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
        $result = @$this->upload([
            'dir' => 'uploads',
            'fileName' => 'cu/stom', // invalid file name
        ]);

        $this->assertFalse($result);
        $this->assertTrue($this->upload->hasError('cantMove'));
    }

    public function testOverwriteUploadFile()
    {
        $this->req->setOption('files', [
            'picture' => [
                'name' => 'overwrite.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
        ]);

        $result1 = $this->upload();
        $file1 = $this->upload->getFile();

        $result2 = $this->upload();
        $file2 = $this->upload->getFile();

        $result3 = $this->upload([
            'overwrite' => true,
        ]);
        $file3 = $this->upload->getFile();

        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertTrue($result3);

        $this->assertFileExists($file1);
        $this->assertFileExists($file2);
        $this->assertFileExists($file3);

        $this->assertNotEquals($file1, $file2);
        $this->assertEquals($file1, $file3);
    }

    public function testUploadFileWithoutExtension()
    {
        $this->req->setOption('files', [
            'picture' => [
                'name' => 'noext',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5',
                'size' => 20,
                'error' => \UPLOAD_ERR_OK,
            ],
        ]);

        $result1 = $this->upload();
        $file1 = $this->upload->getFile();

        $result2 = $this->upload();
        $file2 = $this->upload->getFile();

        $result3 = $this->upload([
            'overwrite' => true,
        ]);
        $file3 = $this->upload->getFile();

        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertTrue($result3);

        $this->assertFileExists($file1);
        $this->assertFileExists($file2);
        $this->assertFileExists($file3);

        $this->assertNotEquals($file1, $file2);
        $this->assertEquals($file1, $file3);
    }
}
