<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsAllTest extends BaseValidatorTestCase
{
    public function testAll()
    {
        $this->assertTrue($this->isAll([
            'apple', 'pear', 'orange',
        ], [
            'in' => [
                ['apple', 'pear', 'orange'],
            ],
        ]));
    }

    public function testNotAll()
    {
        $this->assertFalse($this->isAll([
            'apple', 'pear',
        ], [
            'in' => [
                ['apple', 'pear', 'orange'],
            ],
            'length' => [
                'min' => 5,
                'max' => 10,
            ],
        ]));
    }

    public function testGetMessages()
    {
        $this->isAll([
            'apple',
            'pear', // length invalid
        ], [
            'in' => [
                ['apple', 'pear', 'orange'],
            ],
            'length' => [
                'min' => 5,
                'max' => 10,
            ],
        ]);

        $itemName = 'custom item name';
        $this->isAll->setOption('itemName', $itemName);

        $messages = $this->isAll->getMessages();
        foreach ($messages as $message) {
            $this->assertStringContainsString($itemName, $message);
        }
    }
}
