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
    public function testArrayVal($input)
    {
        $this->assertTrue($this->isArray($input));
    }

    /**
     * @dataProvider providerForNotArrayVal
     * @param mixed $input
     */
    public function testNotArrayVal($input)
    {
        $this->assertFalse($this->isArray($input));
    }

    public function providerForArrayVal()
    {
        return [
            [[]],
            [[1]],
            [new \ArrayObject()],
            [new \SimpleXMLElement('<xml></xml>')],
        ];
    }

    public function providerForNotArrayVal()
    {
        return [
            [1],
            ['1,2,3'],
        ];
    }
}
