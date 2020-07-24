<?php

namespace WeiTest;

/**
 * @property \Wei\Password $password
 * @link https://github.com/ircmaxell/password_compat/blob/master/test/Unit
 *
 * @internal
 */
final class PasswordTest extends TestCase
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

    /**
     * @dataProvider provideInfo
     * @param mixed $hash
     * @param mixed $info
     */
    public function testInfo($hash, $info)
    {
        $this->assertEquals($info, $this->password->getInfo($hash));
    }

    public function provideInfo()
    {
        // Init password service to avoid "PASSWORD_BCRYPT" constant no defined error
        $this->password;

        return [
            ['foo', ['algo' => 0, 'algoName' => 'unknown', 'options' => []]],
            ['$2y$', ['algo' => 0, 'algoName' => 'unknown', 'options' => []]],
            ['$2y$07$', ['algo' => 0, 'algoName' => 'unknown', 'options' => []]],
            ['$2y$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', ['algo' => PASSWORD_BCRYPT, 'algoName' => 'bcrypt', 'options' => ['cost' => 7]]],
            ['$2y$10$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', ['algo' => PASSWORD_BCRYPT, 'algoName' => 'bcrypt', 'options' => ['cost' => 10]]],
        ];
    }
}
