<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class ArrayValTest extends TestCase
{
    /**
     * @dataProvider providerForArrayVal
     * @param mixed $input
     */
    public function testArrayVal($input)
    {
        $this->assertTrue($this->isArrayVal($input));
    }

    /**
     * @dataProvider providerForNotArrayVal
     * @param mixed $input
     */
    public function testNotArrayVal($input)
    {
        $this->assertFalse($this->isArrayVal($input));
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
