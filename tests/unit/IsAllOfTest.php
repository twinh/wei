<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsAllOfTest extends BaseValidatorTestCase
{
    public function testAllOf()
    {
        $this->assertTrue($this->isAllOf('10000@qq.com', [
            'email' => true,
            'endsWith' => [
                'findMe' => '@qq.com',
            ],
        ]));
    }

    public function testNotAllOf()
    {
        $this->assertFalse($this->isAllOf('10000@qq.com', [
            'email' => true,
            'endsWith' => '@gmail.com',
        ]));
    }

    public function testGetMessages()
    {
        $allOf = $this->validate->createRuleValidator('allOf');

        $allOf('10000@qq.com', [
            'email' => true,
            'endsWith' => '@gmail.com',
        ]);

        // Returns single message as default
        $this->assertCount(1, $allOf->getMessages());
    }
}
