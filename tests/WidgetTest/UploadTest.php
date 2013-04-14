<?php

namespace WidgetTest;

class UploadTest extends TestCase
{
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
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'uploadedFiles' => array(
                'upload' => array(
                    'name' => 'test.gif',
                    'type' => 'image/gif',
                    'tmp_name' => '',
                    'error' => $error
                )
            )
        ));
        
        $upload();

        $this->assertTrue($upload->hasError($name));
    }
    
    public function providerForUploadError()
    {
        return array(
            array(
                UPLOAD_ERR_INI_SIZE, 'maxSize'  
            ),
            array(
                UPLOAD_ERR_FORM_SIZE, 'maxSize' 
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
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'uploadedFiles' => array(
                'upload' => array(
                    'name' => 'test.gif',
                    'type' => 'image/gif',
                    'tmp_name' => '',
                    'error' => UPLOAD_ERR_OK
                ),
                'picture2' => array(
                    'name' => 'test.gif',
                    'type' => 'image/gif',
                    'tmp_name' => '',
                    'error' => UPLOAD_ERR_OK
                )
            )
        ));
        
        $upload('picture2');
        $this->assertEquals('picture2', $upload->getOption('field'));
        
        $upload('notThisFiled');
        $this->assertTrue($upload->hasError('noFile'));
    }
    
    public function testInvoker()
    {
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget
        ));
        
        $upload(array(
            'field' => 'upload'
        ));
        
        $this->assertEquals('upload', $upload->getOption('field'));
    }
    
    public function testUploadImage()
    {
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'unitTest' => true,
            'uploadedFiles' => array(
                'picture' => array(
                    'name' => 'test.gif',
                    'type' => 'image/gif',
                    'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                    'error' => UPLOAD_ERR_OK
                )
            ),
            'maxWidth' => 3
        ));
        
        $upload();
        
        $this->assertTrue($upload->hasError('widthTooBig'));
    }
    
    public function testUploadNormalFile()
    {
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'unitTest' => true,
            'uploadedFiles' => array(
                'picture' => array(
                    'name' => 'test.gif',
                    'type' => 'image/gif',
                    'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                    'error' => UPLOAD_ERR_OK
                )
            ),
            'exts' => 'jpg,png'
        ));
        
        $upload();
        
        $this->assertTrue($upload->hasError('exts'));
    }
    
    public function testUploadFileLargerThanMaxPostSize()
    {
        $this->post->fromArray(array());
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'unitTest' => true,
            'uploadedFiles' => array(
                
            )
        ));
        
        $upload('bigFile');
        
        $this->assertTrue($upload->hasError('postSize'));
    }
    
    public function testSaveFile()
    {
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'dir' => 'uploads',
            'unitTest' => true,
            'uploadedFiles' => array(
                'picture' => array(
                    'name' => 'test.gif',
                    'type' => 'image/gif',
                    'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                    'error' => UPLOAD_ERR_OK
                )
            )
        ));    
        
        $upload();
        
        $this->assertFileExists($upload->getFile());
    }
    
    public function testUploadWithCustomName()
    {
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'dir' => 'uploads',
            'fileName' => 'custom',
            'unitTest' => true,
            'uploadedFiles' => array(
                'picture' => array(
                    'name' => 'test.gif',
                    'type' => 'image/gif',
                    'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                    'error' => UPLOAD_ERR_OK
                )
            )
        ));    
        
        $upload();
        
        $file = 'uploads/custom.gif';
        $this->assertEquals($file, $upload->getFile());
        $this->assertFileExists($file);
    }
    
    public function testSaveFileToCustomDir()
    {
        $dir = 'uploads/' . date('Ymd');
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'dir' => $dir,
            'unitTest' => true,
            'uploadedFiles' => array(
                'picture' => array(
                    'name' => 'test.gif',
                    'type' => 'image/gif',
                    'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                    'error' => UPLOAD_ERR_OK
                )
            )
        ));
        
        $result = $upload();
        $file = $upload->getFile();

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
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'dir' => 'uploads',
            'fileName' => 'cu/stom', // invalid file name
            'unitTest' => true,
            'uploadedFiles' => array(
                'picture' => array(
                    'name' => 'test.gif',
                    'type' => 'image/gif',
                    'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                    'error' => UPLOAD_ERR_OK
                )
            )
        ));    
        
        // Avoid Warning: copy(uploads/cus/tom.gif) [function.copy]: failed to open stream: No such file or directory
        $result = @$upload();
        
        $this->assertFalse($result);
        $this->assertTrue($upload->hasError('cantMove'));
    }
}