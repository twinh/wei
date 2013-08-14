<?php

namespace WidgetTest;

class UploadTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->upload->setOption('unitTest', true);
    }

    public static function tearDownAfterClass()
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

    /**
     * @dataProvider providerForUploadError
     */
    public function testUploadError($error, $name)
    {
        $this->request->setOption('files', array(
            'upload' => array(
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => '',
                'size' => 20,
                'error' => $error
            )
        ));

        $this->upload();

        $this->assertTrue($this->upload->hasError($name));
    }

    public function providerForUploadError()
    {
        return array(
            array(
                UPLOAD_ERR_INI_SIZE, 'maxSize'
            ),
            array(
                UPLOAD_ERR_FORM_SIZE, 'formLimit'
            ),
            array(
                UPLOAD_ERR_PARTIAL, 'partial'
            ),
            array(
                UPLOAD_ERR_NO_FILE, 'noFile'
            ),
            array(
                UPLOAD_ERR_NO_TMP_DIR, 'noTmpDir'
            ),
            array(
                UPLOAD_ERR_CANT_WRITE, 'cantWrite'
            ),
            array(
                UPLOAD_ERR_EXTENSION, 'extension'
            ),
            array(
                'noThisError', 'noFile'
            )
        );
    }

    public function testUploadBySpecifiedName()
    {
        $this->request->setOption('files', array(
            'upload' => array(
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => '',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            ),
            'picture2' => array(
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => '',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            )
        ));

        $this->upload('picture2');
        $this->assertEquals('picture2', $this->upload->getOption('field'));

        $this->upload('notThisFiled');
        $this->assertTrue($this->upload->hasError('noFile'));
    }

    public function testInvoker()
    {
        $this->upload(array(
            'field' => 'upload'
        ));

        $this->assertEquals('upload', $this->upload->getOption('field'));
    }

    public function testUploadImage()
    {
        $this->request->setOption('files', array(
            'picture' => array(
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            )
        ));

        $this->upload(array(
            'maxWidth' => 3
        ));


        $this->assertTrue($this->upload->hasError('widthTooBig'));
    }

    public function testUploadNormalFile()
    {
        $this->request->setOption('files', array(
            'picture' => array(
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            )
        ));

        $this->upload(array(
            'exts' => 'jpg,png'
        ));

        $this->assertTrue($this->upload->hasError('exts'));
    }

    public function testUploadFileLargerThanMaxPostSize()
    {
        $this->request->setOption('files', array());
        $this->request->setOption('posts', array());

        $this->upload(array(
            'field' => 'bigFile'
        ));

        $this->assertTrue($this->upload->hasError('postSize'));
    }

    public function testSaveFile()
    {
        $this->request->setOption('files', array(
            'picture' => array(
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            )
        ));

        $this->upload(array(
            'dir' => 'uploads'
        ));

        $this->assertFileExists($this->upload->getFile());
    }

    public function testUploadWithCustomName()
    {
        $this->request->setOption('files', array(
            'picture' => array(
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            )
        ));

        $this->upload(array(
            'dir' => 'uploads',
            'fileName' => 'custom'
        ));

        $file = 'uploads/custom.gif';
        $this->assertEquals($file, $this->upload->getFile());
        $this->assertFileExists($file);
    }

    public function testSaveFileToCustomDir()
    {
        $this->request->setOption('files', array(
            'picture' => array(
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            )
        ));

        $dir = 'uploads/' . date('Ymd');
        $result = $this->upload(array(
            'dir' => $dir
        ));

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
        $this->request->setOption('files', array(
            'picture' => array(
                'name' => 'test.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            )
        ));

        // Avoid Warning: copy(uploads/cus/tom.gif) [function.copy]: failed to open stream: No such file or directory
        $result = @$this->upload(array(
            'dir' => 'uploads',
            'fileName' => 'cu/stom' // invalid file name
        ));

        $this->assertFalse($result);
        $this->assertTrue($this->upload->hasError('cantMove'));
    }

    public function testOverwriteUploadFile()
    {
        $this->request->setOption('files', array(
            'picture' => array(
                'name' => 'overwrite.gif',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            )
        ));

        $result1 = $this->upload();
        $file1 = $this->upload->getFile();

        $result2 = $this->upload();
        $file2 = $this->upload->getFile();

        $result3 = $this->upload(array(
            'overwrite' => true
        ));
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
        $this->request->setOption('files', array(
            'picture' => array(
                'name' => 'noext',
                'type' => 'image/gif',
                'tmp_name' => __DIR__ . '/Fixtures/5x5',
                'size' => 20,
                'error' => UPLOAD_ERR_OK
            )
        ));

        $result1 = $this->upload();
        $file1 = $this->upload->getFile();

        $result2 = $this->upload();
        $file2 = $this->upload->getFile();

        $result3 = $this->upload(array(
            'overwrite' => true
        ));
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