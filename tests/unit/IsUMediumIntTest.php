<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsUMediumIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForUMediumInt
     * @param mixed $input
     */
    public function testUMediumInt($input)
    {
        $this->assertTrue($this->isUMediumInt($input));
    }

    /**
     * @dataProvider providerForNotUMediumInt
     * @param mixed $input
     */
    public function testNotUMediumInt($input)
    {
        $this->assertFalse($this->isUMediumInt($input));
    }

    public function providerForUMediumInt()
    {
        return [
            [1],
            [0],
            [16777215],
        ];
    }

    public function providerForNotUMediumInt()
    {
        return [
            ['1.0'],
            [0 - 1],
            [16777215 + 1],
        ];
    }
}
