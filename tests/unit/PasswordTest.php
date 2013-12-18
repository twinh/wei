<?php

namespace WeiTest;

/**
 * @property \Wei\Password $password
 * @link https://github.com/ircmaxell/password_compat/blob/master/test/Unit
 */
class PasswordTest extends TestCase
{
    /**
     * @dataProvider provideInfo
     */
    public function testInfo($hash, $info)
    {
        $this->assertEquals($info, $this->password->getInfo($hash));
    }

    public static function provideInfo()
    {
        return array(
            array('foo', array('algo' => 0, 'algoName' => 'unknown', 'options' => array())),
            array('$2y$', array('algo' => 0, 'algoName' => 'unknown', 'options' => array())),
            array('$2y$07$', array('algo' => 0, 'algoName' => 'unknown', 'options' => array())),
            array('$2y$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', array('algo' => PASSWORD_BCRYPT, 'algoName' => 'bcrypt', 'options' => array('cost' => 7))),
            array('$2y$10$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', array('algo' => PASSWORD_BCRYPT, 'algoName' => 'bcrypt', 'options' => array('cost' => 10))),
        );
    }

    public function testStringLength()
    {
        $this->assertEquals(60, strlen($this->password->hash('foo')));
    }

    public function testHash()
    {
        $hash = $this->password->hash('foo');
        $this->assertEquals($hash, crypt('foo', $hash));
    }

    public function testKnownSalt()
    {
        $hash = password_hash("rasmuslerdorf", PASSWORD_BCRYPT, array("cost" => 7, "salt" => "usesomesillystringforsalt"));
        $this->assertEquals('$2y$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', $hash);
    }

    public function testRawSalt()
    {
        $hash = password_hash("test", PASSWORD_BCRYPT, array("salt" => "123456789012345678901" . chr(0)));
        $this->assertEquals('$2y$10$MTIzNDU2Nzg5MDEyMzQ1Nej0NmcAWSLR.oP7XOR9HD/vjUuOj100y', $hash);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testInvalidBcryptCostLow()
    {
        password_hash('foo', PASSWORD_BCRYPT, array('cost' => 3));
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testInvalidBcryptCostHigh()
    {
        password_hash('foo', PASSWORD_BCRYPT, array('cost' => 32));
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testInvalidBcryptCostInvalid()
    {
        password_hash('foo', PASSWORD_BCRYPT, array('cost' => 'foo'));
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testInvalidBcryptSaltShort()
    {
        password_hash('foo', PASSWORD_BCRYPT, array('salt' => 'abc'));
    }
}