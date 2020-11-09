<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsImageTest extends BaseValidatorTestCase
{
    public function createImageValidator()
    {
        return new \Wei\IsImage([
            'wei' => $this->wei,
        ]);
    }

    public function testNotFound()
    {
        $image = $this->createImageValidator();

        $this->assertFalse($image('not found file'));
        $this->assertEquals(['notFound'], array_keys($image->getErrors()));
    }

    public function testNotDetected()
    {
        $image = $this->createImageValidator();

        $this->assertFalse($image(__FILE__));
        $this->assertEquals(['notDetected'], array_keys($image->getErrors()));
    }

    public function testSize()
    {
        $image = $this->createImageValidator();

        $this->assertTrue($image(dirname(__DIR__) . '/Fixtures/5x5.gif', [
            'maxHeight' => 10,
            'minHeight' => 0,
            'maxWidth' => 10,
            'minWidth' => 0,
        ]));
    }

    public function testSizeNotPass()
    {
        $image = $this->createImageValidator();

        $this->assertFalse($image(dirname(__DIR__) . '/Fixtures/5x5.gif', [
            'maxHeight' => 4,
            'minHeight' => 6,
            'maxWidth' => 4,
            'minWidth' => 6,
        ]));

        $this->assertEquals(
            ['widthTooBig', 'widthTooSmall', 'heightTooBig', 'heightTooSmall'],
            array_keys($image->getErrors())
        );
    }

    public function testFileNameWithSpace()
    {
        $image = $this->createImageValidator();

        $this->assertTrue($image(dirname(__DIR__) . '/Fixtures/5 x 5.gif', [
            'maxHeight' => 10,
            'minHeight' => 0,
            'maxWidth' => 10,
            'minWidth' => 0,
        ]));
    }
}
