<?php

namespace WeiTest;

/**
 * @property \Wei\Password $password
 * @link https://github.com/ircmaxell/password_compat/blob/master/test/Unit
 */
class PasswordTest extends TestCase
{
    public function testStringLength()
    {
        $this->assertEquals(60, strlen($this->password->hash('foo')));
    }

    public function testHash()
    {
        $hash = $this->password->hash('foo');
        $this->assertEquals($hash, crypt('foo', $hash));
    }

    public function testInvalidBcryptCostLow()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid bcrypt cost parameter specified: 3');
        $this->password->setCost(3);
    }

    public function testInvalidBcryptCostHigh()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid bcrypt cost parameter specified: 32');
        $this->password->setCost(32);
    }

    public function testInvalidBcryptCostInvalid()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid bcrypt cost parameter specified: foo');
        $this->password->setCost('foo');
    }

    public function testInvalidBcryptSaltShort()
    {
        $this->setExpectedException('InvalidArgumentException', 'Provided salt is too short: 3 expecting 22');
        $this->password->hash('foo', 'abc');
    }

    /**
     * @dataProvider provideInfo
     */
    public function testInfo($hash, $info)
    {
        $this->assertEquals($info, $this->password->getInfo($hash));
    }

    public function provideInfo()
    {
        // Init password service to avoid "PASSWORD_BCRYPT" constant no defined error
        $this->password;

        return array(
            array('foo', array('algo' => 0, 'algoName' => 'unknown', 'options' => array())),
            array('$2y$', array('algo' => 0, 'algoName' => 'unknown', 'options' => array())),
            array('$2y$07$', array('algo' => 0, 'algoName' => 'unknown', 'options' => array())),
            array('$2y$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', array('algo' => PASSWORD_BCRYPT, 'algoName' => 'bcrypt', 'options' => array('cost' => 7))),
            array('$2y$10$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', array('algo' => PASSWORD_BCRYPT, 'algoName' => 'bcrypt', 'options' => array('cost' => 10))),
        );
    }
}