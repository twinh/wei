<?php

namespace WeiTest;

use Wei\IsEach;
use Wei\V;

/**
 * @internal
 */
final class IsEachTest extends BaseValidatorTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function getInputTestOptions()
    {
        return [
            'validator' => wei()->validate,
        ];
    }

    public function testEachSuc()
    {
        $v = V::new();
        $v->key('products')->each(static function (V $v) {
            $v->key('name')->maxLength(5);
            $v->key('stock')->greaterThanOrEqual(0);
        });
        $ret = $v->check([
            'products' => [
                [
                    'name' => 'name',
                    'stock' => 1,
                ],
                [
                    'name' => 'name',
                    'stock' => 1,
                ],
            ],
        ]);

        $this->assertRetSuc($ret);
    }

    public function testEach()
    {
        $v = V::new();
        $v->key('users', '用户')->each(static function (V $v) {
            $v->string('name', '姓名')->minLength(3);
            $v->string('email', '邮箱')->email();
        });
        $ret = $v->check([
            'users' => [
                [
                    'name' => 'test',
                    'email' => 'test@example.com',
                ],
                [
                    'name' => 't',
                    'email' => 't',
                ],
            ],
        ]);
        $this->assertRetErr($ret, 'The 2nd 用户\'s 姓名 must have a length greater than 3');
    }

    /**
     * @dataProvider providerForEachNested
     */
    public function testEachNested(string $lang, string $message)
    {
        wei()->t->setLocale($lang);

        $v = V::new();
        $v->key('users', '用户')->each(static function (V $v) {
            $v->array('emails', '邮箱')->each(static function (V $v) {
                $v->string('address', '地址')->email();
            });
        });
        $ret = $v->check([
            'users' => [
                [
                    'emails' => [
                        [
                            'address' => 'test@example.com',
                        ],
                    ],
                ],
                [
                    'emails' => [
                        [
                            'address' => 'test@example.com',
                        ],
                        [
                            'address' => 'test@example.com',
                        ],
                        [
                            'address' => 'test',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertRetErr($ret, $message);
    }

    public function providerForEachNested()
    {
        return [
            ['en', 'The 2nd 用户\'s 3rd 邮箱\'s 地址 must be valid email address'],
            ['zh-CN', '第 2 个用户的第 3 个邮箱的地址必须是有效的邮箱地址'],
        ];
    }

    public function testEachCollSuc()
    {
        $v = V::label('用户')->each(static function (V $v) {
            $v->key('name')->maxLength(5);
            $v->key('stock')->greaterThanOrEqual(0);
        });
        $ret = $v->check([
            [
                'name' => 'name',
                'stock' => 1,
            ],
            [
                'name' => 'name',
                'stock' => 1,
            ],
        ]);

        $this->assertRetSuc($ret);
    }

    public function testEachCollErr()
    {
        $v = V::label('用户')->each(static function (V $v) {
            $v->string('name', '姓名')->minLength(3);
            $v->string('email', '邮箱')->email();
        });
        $ret = $v->check([
            [
                'name' => 'test',
                'email' => 'test@example.com',
            ],
            [
                'name' => 't',
                'email' => 't',
            ],
        ]);
        $this->assertRetErr($ret, 'The 2nd 用户\'s 姓名 must have a length greater than 3');
    }

    public function testVParameter()
    {
        $eachV = V::new();
        $eachV->string('name', '姓名')->minLength(3);
        $eachV->string('email', '邮箱')->email();

        $v = V::new();
        $v->key('users', '用户')->each($eachV);
        $ret = $v->check([
            'users' => [
                [
                    'name' => 'test',
                    'email' => 'test@example.com',
                ],
                [
                    'name' => 't',
                    'email' => 't',
                ],
            ],
        ]);
        $this->assertRetErr($ret, 'The 2nd 用户\'s 姓名 must have a length greater than 3');
    }

    public function testNotArray()
    {
        $v = V::new();
        $v->key('users', '用户')->each(static function (V $v) {
            $v->string('name', '姓名')->minLength(3);
            $v->string('email', '邮箱')->email();
        });
        $ret = $v->check(['users' => null]);
        $this->assertRetErr($ret, '用户 must be an array');
    }

    public function testCallWithoutValidator()
    {
        $this->expectExceptionObject(
            new \LogicException('The "each" validator should not call directly, please use with \Wei\V')
        );
        $this->isEach('test');
    }

    public function testCallbackCreateNewValidator()
    {
        $validators = [];

        $v = V::new();
        $v->key('users', '用户')->each(static function (V $v) use (&$validators) {
            $validators[] = $v;
            $v->string('name', '姓名')->minLength(3);
            $v->string('email', '邮箱')->email();
        });
        $ret = $v->check([
            'users' => [
                [
                    'name' => 'test',
                    'email' => 'test@example.com',
                ],
                [
                    'name' => 't',
                    'email' => 't',
                ],
            ],
        ]);
        $this->assertRetErr($ret, 'The 2nd 用户\'s 姓名 must have a length greater than 3');
        $this->assertNotSame($validators[0], $validators[1]);
    }

    public function testCallbackGetData()
    {
        $data = [
            [
                'name' => 'test',
                'email' => 'test@example.com',
            ],
            [
            ],
        ];

        $validateData = [];
        $v = V::new();
        $v->key('users', '用户')->each(static function (V $v) use (&$validateData) {
            $validateData[] = $v->getData();
            $v->string('name', '姓名')->minLength(3);
            $v->string('email', '邮箱')->email();
        });
        $ret = $v->check(['users' => $data]);
        $this->assertRetErr($ret, 'The 2nd 用户\'s 姓名 is required');
        $this->assertSame($validateData, $data);
    }

    public function testCallbackKeys()
    {
        $keys = [];

        $v = V::new();
        $v->key('users', '用户')->each(static function (V $v, IsEach $isEach) use (&$keys) {
            $keys[] = $isEach->getCurKey();
            $v->string('name', '姓名')->minLength(3);
            $v->string('email', '邮箱')->email();
        });
        $ret = $v->check([
            'users' => [
                [
                    'name' => 'test',
                    'email' => 'test@example.com',
                ],
                [
                    'name' => 't',
                    'email' => 't',
                ],
            ],
        ]);
        $this->assertRetErr($ret, 'The 2nd 用户\'s 姓名 must have a length greater than 3');
        $this->assertSame($keys, [0, 1]);
    }
}
