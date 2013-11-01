<?php

namespace WeiTest\Validator;

class NoneOfTest extends TestCase
{
    public function testNoneOf()
    {
        $this->assertTrue($this->isNoneOf('10000@qq.com', array(
            'digit' => true,
            'endsWith' => array(
                'findMe' => '@gmail.com'
            )
        )));
    }

    public function testNotNoneOf()
    {
        $this->assertFalse($this->isNoneOf('10000@qq.com', array(
            'email' => true,
            'endsWith' => array(
                'findMe' => '@gmail.com'
            )
        )));
    }

    public function testGetMessages()
    {
        $noneOf = $this->validate->createRuleValidator('noneOf');

        $noneOf('abc', array(
            'equalTo' => 'abc',
            'alnum' => true
        ));

        // Returns multi messages as default
        $this->assertCount(2, $noneOf->getMessages());
    }

    public function testIntAsFindMeOption()
    {
        $result = $this->isNoneOf('game@', array(
            'email' => true,
            'endsWith' => 1233
        ));
        $this->assertTrue($result);

        $result = $this->isNoneOf('game@', array(
            'email' => true,
            'endsWith' => "123"
        ));
        $this->assertTrue($result);
    }
}
