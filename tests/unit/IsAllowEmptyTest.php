<?php

namespace WeiTest;

use Wei\V;

/**
 * @internal
 */
final class IsAllowEmptyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function testString()
    {
        $ret = V::allowEmpty()->email()->check('');
        $this->assertRetSuc($ret);
    }

    public function testNull()
    {
        $ret = V::allowEmpty()->email()->check(null);
        $this->assertRetSuc($ret);
    }

    public function testValid()
    {
        $ret = V::email()->check('test@example.com');
        $this->assertRetSuc($ret);

        $ret = V::allowEmpty()->email()->check('test@example.com');
        $this->assertRetSuc($ret);
    }

    public function testMobileCn()
    {
        $v = V::new();
        $v->mobileCn('mobile', 'The mobile')->allowEmpty();

        $ret = $v->check([
            // [] is empty, but not allow for string
            'mobile' => [],
        ]);
        $this->assertRetErr($ret, 'The mobile must be a string');

        $ret = $v->check([
            'mobile' => '',
        ]);
        $this->assertRetSuc($ret);
    }

    public function testMultiTypes()
    {
        $v = V::new();
        $v->between('age', 'The age', 10, 20)->allowEmpty();
        $ret = $v->check([
            'age' => '',
        ]);
        $this->assertRetSuc($ret);
    }

    /**
     * @dataProvider providerForAllowEmpty
     * @param mixed $value
     */
    public function testAllowEmpty(string $type, $value, bool $result, string $message = null)
    {
        $v = V::new();
        $v->key('key', 'label')->addRule($type, [])->allowEmpty();
        $ret = $v->check([
            'key' => $value,
        ]);
        $this->assertSame($result, $ret->isSuc());
        if ($ret->isErr()) {
            $this->assertRetErr($ret, $message);
        }
    }

    public function providerForAllowEmpty()
    {
        return [
            ['string', '', true],
            ['string', null, true],
            ['string', [], false, 'label must be a string'],
            ['string', false, true],

            ['int', '', true],
            ['int', null, true],
            ['int', [], false, 'label must be an integer value'],
            ['int', false, true],

            ['float', '', true],
            ['float', null, true],
            ['float', [], false, 'label must be a float value'],
            ['float', false, true],

            ['bool', '', true],
            ['bool', null, true],
            ['bool', [], false, 'label must be a bool value'],
            ['bool', false, true],

            ['array', '', true],
            ['array', null, true],
            ['array', [], true],
            ['array', false, false, 'label must be an array'],
        ];
    }
}
