<?php

namespace WeiTest;

/**
 * @internal
 * @mixin \IsLengthMixin
 */
final class IsLengthTest extends BaseValidatorTestCase
{
    protected $arrayObject;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->arrayObject = new \ArrayObject([
            1,
            2,
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

    public static function providerForSpecifiedLength()
    {
        return [
            ['length7', 7],
            [[1, 2], 2],
            [$this->arrayObject, 2],
        ];
    }

    public static function providerForSpecifiedLengthNotPass()
    {
        return [
            ['length7', 8],
            [[1, 2], 3],
            [$this->arrayObject, 3],
        ];
    }

    public static function providerForLength()
    {
        return [
            ['length7', 7, 10],
            ['length7', 0, 10],
            [[1, 2], 1, 2],
            [$this->arrayObject, 1, 10],
        ];
    }

    public static function providerForNotLength()
    {
        return [
            ['length7', 0, 0],
            ['length7', -2, -1],
            [[1, 2], 10, 0],
            [$this->arrayObject, 0, 1],
        ];
    }

    public function testCountByChars()
    {
        $this->assertTrue($this->isLength('我爱你', 9));

        $this->isLength->storeOption('countByChars', true);
        $this->assertTrue($this->isLength('我爱你', 3));

        $this->isLength->storeOption('countByChars', true);
        $this->assertFalse($this->isLength([1, 2], 2));
    }

    public function testNull()
    {
        $this->assertFalse($this->isLength(null, 1));
        $this->assertTrue($this->isLength(null, 0));
    }
}
