<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class LengthTest extends TestCase
{
    protected $ao;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->ao = new \ArrayObject([
            1, 2,
        ]);
    }

    /**
     * @dataProvider providerForLength
     * @param mixed $input
     * @param mixed $option1
     * @param mixed $option2
     */
    public function testLength($input, $option1, $option2)
    {
        $this->assertTrue($this->isLength($input, $option1, $option2));
    }

    /**
     * @dataProvider providerForNotLength
     * @param mixed $input
     * @param mixed $option1
     * @param mixed $option2
     */
    public function testNotLength($input, $option1, $option2)
    {
        $this->assertFalse($this->isLength($input, $option1, $option2));
    }

    /**
     * @dataProvider providerForSpecifiedLength
     * @param mixed $input
     * @param mixed $length
     */
    public function testSpecifiedLength($input, $length)
    {
        $this->assertTrue($this->isLength($input, $length));
    }

    /**
     * @dataProvider providerForSpecifiedLengthNotPass
     * @param mixed $input
     * @param mixed $length
     */
    public function testSpecifiedLengthNotPass($input, $length)
    {
        $this->assertFalse($this->isLength($input, $length));
    }

    public function providerForSpecifiedLength()
    {
        return [
            ['length7', 7],
            [[1, 2], 2],
            [$this->ao, 2],
        ];
    }

    public function providerForSpecifiedLengthNotPass()
    {
        return [
            ['length7', 8],
            [[1, 2], 3],
            [$this->ao, 3],
        ];
    }

    public function providerForLength()
    {
        return [
            ['length7', 7, 10],
            ['length7', 0, 10],
            [[1, 2], 1, 2],
            [$this->ao, 1, 10],
        ];
    }

    public function providerForNotLength()
    {
        return [
            ['length7', 0, 0],
            ['length7', -2, -1],
            [[1, 2], 10, 0],
            [$this->ao, 0, 1],
        ];
    }
}
