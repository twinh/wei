<?php

namespace WidgetTest\Validator;

class LengthTest extends TestCase
{
    protected $ao;

    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->ao = new \ArrayObject(array(
            1, 2,
        ));
    }

    /**
     * @dataProvider providerForLength
     */
    public function testLength($input, $option1, $option2)
    {
        $this->assertTrue($this->isLength($input, $option1, $option2));
    }

    /**
     * @dataProvider providerForNotLength
     */
    public function testNotLength($input, $option1, $option2)
    {
        $this->assertFalse($this->isLength($input, $option1, $option2));
    }

    /**
     * @dataProvider providerForSpecifiedLength
     */
    public function testSpecifiedLength($input, $length)
    {
        $this->assertTrue($this->isLength($input, $length));
    }

    /**
     *
     * @dataProvider providerForSpecifiedLengthNotPass
     */
    public function testSpecifiedLengthNotPass($input, $length)
    {
        $this->assertFalse($this->isLength($input, $length));
    }

    public function providerForSpecifiedLength()
    {
        return array(
            array('length7', 7),
            array(array(1, 2), 2),
            array($this->ao, 2)
        );
    }

    public function providerForSpecifiedLengthNotPass()
    {
        return array(
            array('length7', 8),
            array(array(1, 2), 3),
            array($this->ao, 3)
        );
    }

    public function providerForLength()
    {
        return array(
            array('length7', 7, 10),
            array('length7', 0, 10),
            array(array(1, 2), 1, 2),
            array($this->ao, 1, 10),
        );
    }

    public function providerForNotLength()
    {
        return array(
            array('length7', 0, 0),
            array('length7', -2, -1),
            array(array(1, 2), 10, 0),
            array($this->ao, 0, 1),
        );
    }
}