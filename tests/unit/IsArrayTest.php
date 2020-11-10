<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsArrayTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForArrayVal
     * @param mixed $input
     */
    public function testArrayVal($input, int $minLength = null, int $maxLength = null)
    {
        $this->assertTrue($this->isArray($input, $minLength, $maxLength));
    }

    /**
     * @dataProvider providerForNotArrayVal
     * @param mixed $input
     */
    public function testNotArrayVal($input, int $minLength = null, int $maxLength = null)
    {
        $this->assertFalse($this->isArray($input, $minLength, $maxLength));
    }

    public function providerForArrayVal()
    {
        return [
            [[]],
            [[1]],
            [new \ArrayObject()],
            [new \SimpleXMLElement('<xml></xml>')],
            [[1, 2], 1],
            [[1, 2], null, 3],
            [[1, 2], 1, 3],
            [[1, 2], 2, 3],
        ];
    }

    public function providerForNotArrayVal()
    {
        return [
            [1],
            ['1,2,3'],
            [[1, 2], 3],
            [[1, 2], null, 1],
            [[1, 2], 0, 1],
        ];
    }
}
