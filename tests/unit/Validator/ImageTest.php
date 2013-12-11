<?php

namespace WeiTest\Validator;

class ImageTest extends TestCase
{
    public function createImageValidator()
    {
        return new \Wei\Validator\Image(array(
            'wei' => $this->wei,
        ));
    }

    public function testNotFound()
    {
        $image = $this->createImageValidator();

        $this->assertFalse($image('not found file'));
        $this->assertEquals(array('notFound'), array_keys($image->getErrors()));
    }

    public function testNotDetected()
    {
        $image = $this->createImageValidator();

        $this->assertFalse($image(__FILE__));
        $this->assertEquals(array('notDetected'), array_keys($image->getErrors()));
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

        $this->assertEquals(
            array('widthTooBig', 'widthTooSmall', 'heightTooBig', 'heightTooSmall'),
            array_keys($image->getErrors())
        );
    }

    public function testFileNameWithSpace()
    {
        $image = $this->createImageValidator();

        $this->assertTrue($image(dirname(__DIR__) . '/Fixtures/5 x 5.gif', array(
            'maxHeight' => 10,
            'minHeight' => 0,
            'maxWidth' => 10,
            'minWidth' => 0
        )));
    }
}
