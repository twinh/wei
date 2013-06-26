<?php

namespace WidgetTest\Validator;

/**
 * @todo more space test
 * @link http://en.wikipedia.org/wiki/Space_(punctuation)
 */
class BlankTest extends TestCase
{
    /**
     * @dataProvider providerForBlank
     */
    public function testBlank($input)
    {
        $this->assertTrue($this->isBlank($input));
    }

    /**
     * @dataProvider providerForNotBlank
     */
    public function testNotBlank($input)
    {
        $this->assertFalse($this->isBlank($input));
    }

    public function providerForBlank()
    {
        return array(
            array('   '),
            array(" \r\n"),
        );
    }

    public function providerForNotBlank()
    {
        return array(
            array(' abc '),
            array('a b'),
            array('　'), // Chinse fullwith space
        );
    }
}
