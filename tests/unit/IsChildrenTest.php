<?php

namespace WeiTest;

use Wei\V;

/**
 * @internal
 */
final class IsChildrenTest extends BaseValidatorTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function testChildrenSuc()
    {
        $ret = V
            ::key('configs')->children(
                V
                    ::key('key1', '配置1')->minLength(3)
                    ->key('key2', '配置2')->minLength(2)
            )
            ->check([
                'configs' => [
                    'key1' => '123',
                    'key2' => '22',
                ],
            ]);

        $this->assertRetSuc($ret);
    }

    public function testChildrenErr()
    {
        $ret = V
            ::key('configs')->children(
                V
                    ::key('key1', '配置1')->minLength(3)
                    ->key('key2', '配置2')->minLength(2)
            )
            ->check([
                'configs' => [
                    'key1' => '1',
                    'key2' => '2',
                ],
            ]);

        $this->assertRetErr($ret, '配置1 must have a length greater than 3');
    }

    public function testChildrenNestedErr()
    {
        $ret = V
            ::key('configs')
            ->children(
                V
                    ::key('key1', '配置1')->minLength(3)
                    ->key('key2', '配置2')->children(
                        V::key('key2.1', '配置2.1')->minLength(2)
                    )
            )
            ->check([
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
        $ret = V
            ::key('configs', '配置')->children(
                V::key('key1', '配置1')->minLength(3)
            )
            ->check([
                'configs' => 123,
            ]);

        $this->assertRetErr($ret, '配置 must be array or object');
    }

    protected function getInputTestOptions()
    {
        return [
            'v' => V::key('name'),
        ];
    }
}
