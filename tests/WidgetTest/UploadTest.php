<?php

namespace WidgetTest;

class UploadTest extends TestCase
{
    /**
     * @dataProvider providerForUploadError
     */
    public function testUploadError($error, $name)
    {
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'uploadedFiles' => array(
                'upload' => array(
                    'name' => 'test.jpg',
                    'type' => 'image/jpeg',
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
            )
        );
    }
    
    public function testUploadBySpecifiedName()
    {
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'uploadedFiles' => array(
                'upload' => array(
                    'name' => 'test.jpg',
                    'type' => 'image/jpeg',
                    'tmp_name' => '',
                    'error' => UPLOAD_ERR_OK
                ),
                'picture2' => array(
                    'name' => 'test.jpg',
                    'type' => 'image/jpeg',
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
    
    public function testUploadImageWidth()
    {
        $upload = new \Widget\Upload(array(
            'widget' => $this->widget,
            'unitTest' => true,
            'uploadedFiles' => array(
                'picture' => array(
                    'name' => 'test.jpg',
                    'type' => 'image/jpeg',
                    'tmp_name' => __DIR__ . '/Fixtures/5x5.gif',
                    'error' => UPLOAD_ERR_OK
                )
            ),
            'maxWidth' => 3
        ));
        
        $upload();
        
        $this->assertTrue($upload->hasError('widthTooBig'));
    }
}