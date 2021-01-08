<?php

namespace WeiTest;

use Wei\V;

/**
 * @internal
 */
final class IsEachTest extends BaseValidatorTestCase
{
    public function getInputTestOptions()
    {
        return [
            'validator' => wei()->validate,
        ];
    }

    public function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function testEachSuc()
    {
        $ret = V
            ::key('products')->each(
                V
                    ::key('name')->maxLength(5)
                    ->key('stock')->greaterThanOrEqual(0)
            )
            ->check([
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
        $ret = V
            ::key('users', '用户')->each(
                V
                    ::string('name', '姓名')->minLength(3)
                    ->string('email', '邮箱')->email()
            )
            ->check([
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

        $ret = V
            ::key('users', '用户')->each(
                V::array('emails', '邮箱')->each(
                    V::string('address', '地址')->email()
                )
            )
            ->check([
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
        $ret = V
            ::each(
                V
                    ::key('name')->maxLength(5)
                    ->key('stock')->greaterThanOrEqual(0)
            )
            ->check([
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
        $ret = V::label('用户')->each(
            V
                ::string('name', '姓名')->minLength(3)
                ->string('email', '邮箱')->email()
        )
            ->check([
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

    public function testCallableParameter()
    {
        $ret = V
            ::key('users', '用户')->each(function (V $v) {
                $v->string('name', '姓名')->minLength(3)
                    ->string('email', '邮箱')->email();
            })
            ->check([
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
        $ret = V
            ::key('users', '用户')->each(
                V
                    ::string('name', '姓名')->minLength(3)
                    ->string('email', '邮箱')->email()
            )->check(['users' => null]);
        $this->assertRetErr($ret, '用户 must be an array');
    }

    public function testCallWithoutValidator()
    {
        $this->expectExceptionObject(new \LogicException('The "each" validator should not call directly, please use with \Wei\V'));
        $this->isEach('test');
    }
}
