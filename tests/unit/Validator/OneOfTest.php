<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class OneOfTest extends TestCase
{
    public function testOneOf()
    {
        $this->assertTrue($this->isOneOf('13', [
            'type' => 'int',
            'alnum' => true,
        ]));
    }

    public function testNotOneOf()
    {
        $this->assertFalse($this->isOneOf('13', [
            'email' => true,
            'length' => [3, 6],
        ]));
    }
}
