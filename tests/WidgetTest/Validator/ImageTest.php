<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class ImageTest extends TestCase
{
    public function createImageValidator()
    {
        return new \Widget\Validator\Image(array(
            'widget' => $this->widget,
        ));
    }
    
    public function testNotFound()
    {
        $image = $this->createImageValidator();
        
        $this->assertFalse($image('not found file'));        
        $this->assertEquals(array(
            'notFound' => array()
        ), $image->getErrors());
    }
    
    public function testNotDetected()
    {
        $image = $this->createImageValidator();
        
        $this->assertFalse($image(__FILE__));
        $this->assertEquals(array(
            'notDetected' => array()
        ), $image->getErrors());
    }
    
    public function testSize()
    {
        $image = $this->createImageValidator();
        
        $this->assertTrue($image(dirname(__DIR__) . '/Fixtures/5x5.gif', array(
            'maxHeight' => 10,
            'minHeight' => 0,
            'maxWidth' => 10,
            'minWidth' => 0
        )));
    }
    
    public function testSizeNotPass()
    {
        $image = $this->createImageValidator();
        
        $this->assertFalse($image(dirname(__DIR__) . '/Fixtures/5x5.gif', array(
            'maxHeight' => 4,
            'minHeight' => 6,
            'maxWidth' => 4,
            'minWidth' => 6
        )));
        
        $this->assertEquals(array(
            'widthTooBig' => array(
                'maxWidth' => 4,
                'width' => 5
            ),
            'widthTooSmall' => array(
                'minWidth' => 6,
                'width' => 5
            ),
            'heightTooBig' => array(
                'maxHeight' => 4,
                'height' => 5
            ),
            'heightTooSmall' => array(
                'minHeight' => 6,
                'height' => 5
            )
        ), $image->getErrors());
    }
}