<?php

namespace WeiTest;

use Wei\V;

/**
 * @internal
 */
final class IsChildrenTest extends BaseValidatorTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function testChildrenSuc()
    {
        $v = V::new();
        $v->key('configs')->children(call_user_func(static function () {
            $v = V::new();
            $v->key('key1', '配置1')->minLength(3);
            $v->key('key2', '配置2')->minLength(2);
            return $v;
        }));
        $ret = $v->check([
            'configs' => [
                'key1' => '123',
                'key2' => '22',
            ],
        ]);

        $this->assertRetSuc($ret);
    }

    public function testChildrenErr()
    {
        $v = V::new();
        $v->key('configs')->children(call_user_func(static function () {
            $v = V::new();
            $v->key('key1', '配置1')->minLength(3);
            $v->key('key2', '配置2')->minLength(2);
            return $v;
        }));
        $ret = $v->check([
            'configs' => [
                'key1' => '1',
                'key2' => '2',
            ],
        ]);

        $this->assertRetErr($ret, '配置1 must have a length greater than 3');
    }

    public function testChildrenNestedErr()
    {
        $v = V::new();
        $v->key('configs')->children(call_user_func(static function () {
            $v = V::new();
            $v->key('key1', '配置1')->minLength(3);
            $v->key('key2', '配置2')->children(call_user_func(static function () {
                $v = V::new();
                $v->key('key2.1', '配置2.1')->minLength(2);
                return $v;
            }));
            return $v;
        }));
        $ret = $v->check([
            'configs' => [
                'key1' => '123',
                'key2' => [
                    'key2.1' => '1',
                ],
            ],
        ]);

        $this->assertRetErr($ret, '配置2.1 must have a length greater than 2');
    }

    public function testInvalidInputType()
    {
        $v = V::new();
        $v->key('configs', '配置')->children(call_user_func(static function () {
            $v = V::new();
            $v->key('key1', '配置1')->minLength(3);
            return $v;
        }));
        $ret = $v->check([
            'configs' => 123,
        ]);

        $this->assertRetErr($ret, '配置 must be array or object');
    }

    protected function getInputTestOptions()
    {
        return [
            'v' => V::new()->key('name'),
        ];
    }
}
