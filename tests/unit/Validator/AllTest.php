<?php

namespace WeiTest\Validator;

class AllTest extends TestCase
{
    public function testAll()
    {
        $this->assertTrue($this->isAll(array(
            'apple', 'pear', 'orange',
        ), array(
            'in' => array(
                array('apple', 'pear', 'orange')
            )
        )));
    }

    public function testNotAll()
    {
        $this->assertFalse($this->isAll(array(
            'apple', 'pear',
        ), array(
            'in' => array(
                array('apple', 'pear', 'orange')
            ),
            'length' => array(
                'min' => 5,
                'max' => 10
            )
        )));
    }

    public function testGetMessages()
    {
        $this->isAll(array(
            'apple',
            'pear', // length invalid
        ), array(
            'in' => array(
                array('apple', 'pear', 'orange')
            ),
            'length' => array(
                'min' => 5,
                'max' => 10
            )
        ));

        $itemName = 'custom item name';
        $this->isAll->setOption('itemName', $itemName);

        $messages = $this->isAll->getMessages();
        foreach ($messages as $message) {
            $this->assertStringContainsString($itemName, $message);
        }
    }
}
