<?php

namespace WeiTest;

use Wei\IsEmail;
use Wei\Ret;
use Wei\Ret\RetException;
use Wei\V;

/**
 * @internal
 */
final class VTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function testCreateNewInstance()
    {
        $this->assertNotSame(V::label('test'), V::label('test'));
    }

    public function testKey()
    {
        $v = V::new();
        $v->key('question', 'Question');

        $ret = $v->check([]);
        $this->assertRetErr($ret, 'Question is required', -1);

        $ret = $v->check([
            'question' => 1,
        ]);
        $this->assertRetSuc($ret);
    }

    public function testSelf()
    {
        $v = V::new();
        $v->self()->maxLength(1);
        $v->maxLength('item', 'The item', 2);

        $ret = $v->check([1, 2]);
        $this->assertRetErr($ret, 'This value must contain no more than 1 items');

        $ret = $v->check([1]);
        $this->assertRetErr($ret, 'The item is required');

        $ret = $v->check([
            'item' => [1, 2, 3],
        ]);
        $this->assertRetErr($ret, 'The item must contain no more than 2 items');

        $ret = $v->check([
            'item' => [1, 2],
        ]);
        $this->assertRetSuc($ret);
    }

    public function testLabel()
    {
        $v = V::new();
        $v->key('question')->label('The question');

        $ret = $v->check([]);
        $this->assertRetErr($ret, 'The question is required', -1);
    }

    public function testLabelStaticCall()
    {
        $ret = V::label('The age')->number()->check(null);
        $this->assertRetErr($ret, 'The age must be valid number');
    }

    public function testLabelWithoutKeyCheckErr()
    {
        $ret = V::label('Mobile')
            ->mobileCn()
            ->check('123');

        $this->assertRetErr($ret, 'Mobile must be valid mobile number', -1);
    }

    public function testLabelWithoutKeyCheckSuc()
    {
        $ret = V::label('Mobile')
            ->mobileCn()
            ->check('13800138000');

        $this->assertRetSuc($ret);
    }

    public function testMessage()
    {
        $v = V::new();
        $v->key('name', '名称')->message('required', '请填写%name%');
        $ret = $v->check([]);

        $this->assertRetErr($ret, '请填写名称', -1);
    }

    public function testMessageWithoutRule()
    {
        $v = V::new();
        $v->key('name', '名称')->required()->message('请填写%name%');
        $ret = $v->check([]);

        $this->assertRetErr($ret, '请填写名称', -1);
    }

    public function testSetDataCheck()
    {
        $v = V::email();

        $ret = $v->setData('test')->check();
        $this->assertRetErr($ret);

        $ret = $v->setData('test@test.com')->check();
        $this->assertRetSuc($ret);
    }

    public function testSetDataIsValid()
    {
        $v = V::email();

        $result = $v->setData('test')->isValid();
        $this->assertFalse($result);

        $result = $v->setData('test@test.com')->isValid();
        $this->assertTrue($result);
    }

    public function testIsValid()
    {
        $result = V::mobileCn()->isValid('123');
        $this->assertFalse($result);

        $result = V::mobileCn()->isValid('13800138000');
        $this->assertTrue($result);
    }

    public function testAssertErr()
    {
        $this->expectExceptionObject(new RetException(Ret::err('The mobile must be valid mobile number')));

        $v = V::new();
        $v->mobileCn('mobile', 'The mobile');
        $v->assert(['mobile' => '123']);
    }

    public function testAssertSuc()
    {
        $v = V::new();
        $v->mobileCn('mobile', '手机');
        $data = $v->assert(['mobile' => '13800138000']);
        $this->assertSame(['mobile' => '13800138000'], $data);
    }

    public function testValidate()
    {
        $v = V::new();
        $v->key('name', 'Name');
        $v->key(['user', 'sex'])->in(['array' => [0, 1, 2]]);
        $v->key(['user', 'email'], 'Email')->email()->message('Invalid %name%');
        $validator = $v->validate([
            'name' => 'test',
            'user' => [
                'sex' => 1,
                'email' => 'test',
            ],
        ]);

        $this->assertSame('email', $validator->getCurrentRule());
        $this->assertSame(['user', 'email'], $validator->getCurrentField());

        $this->assertSame(['name', ['user', 'sex']], $validator->getValidFields());
        $this->assertSame([['user', 'email']], $validator->getInvalidFields());

        $this->assertSame([
            'user.email' => [
                'email' => [
                    'format' => 'Invalid Email',
                ],
            ],
        ], $validator->getDetailMessages());

        $this->assertSame([
            'user.email' => [
                'Invalid Email',
            ],
        ], $validator->getSummaryMessages());

        $this->assertSame([
            'user.email-email-format' => 'Invalid Email',
        ], $validator->getFlatMessages());

        $this->assertSame([
            'name' => [],
            'user.sex' => [],
            'user.email' => ['email' => 'Invalid %name%'],
        ], $validator->getMessages());

        $this->assertSame(['email' => []], $validator->getFieldRules(['user', 'email']));

        $this->assertSame('test', $validator->getFieldData(['user', 'email']));

        $this->assertInstanceOf(IsEmail::class, $validator->getRuleValidator(['user', 'email'], 'email'));

        $this->assertSame(['required'], $validator->getValidRules(['user', 'email']));
        $this->assertSame(['email'], $validator->getInvalidRules(['user', 'email']));

        $this->assertFalse($validator->isFieldValid(['user', 'email']));
        $this->assertTrue($validator->isFieldInvalid(['user', 'email']));

        $this->assertSame([], $validator->getRuleParams(['user', 'email'], 'email'));

        $this->assertTrue($validator->hasField(['user', 'email']));
        $this->assertSame('test', $validator->getFieldData(['user', 'email']));

        $validator->addValidRule(['user.email'], 'test');
        $this->assertSame(['required', 'test'], $validator->getValidRules(['user', 'email']));

        $validator->addInvalidRule(['user.email'], 'test');
        $this->assertSame(['email', 'test'], $validator->getInvalidRules(['user', 'email']));

        $validator->addRule(['user', 'email'], 'test', 'test params');
        $this->assertSame(['test params'], $validator->getRuleParams(['user', 'email'], 'test'));

        $validator->addRule(['user', 'email'], 'test', 'test params');
        $this->assertSame(['test params'], $validator->getRuleParams(['user', 'email'], 'test'));

        $this->assertTrue($validator->hasRule(['user', 'email'], 'test'));

        $this->assertFalse($validator->removeRule(['user', 'email2'], 'test'));
        $this->assertTrue($validator->removeRule(['user', 'email'], 'test'));

        $this->assertFalse($validator->removeField(['user', 'email2']));
        $this->assertTrue($validator->removeField(['user', 'email']));
    }

    public function testDefaultOptional()
    {
        $v = V::defaultOptional();
        $v->key('email')->email();
        $ret = $v->check([]);
        $this->assertRetSuc($ret);
    }

    public function testDefaultRequired()
    {
        $v = V::defaultRequired();
        $v->key('email')->email();
        $ret = $v->check([]);
        $this->assertRetErr($ret, 'This value is required');
    }

    public function testGetOptions()
    {
        $v = V::new();
        $v->key('email', 'Email')->email();
        $v->key('name', 'Name')->length(['max' => 1, 'countByChars' => true]);
        $this->assertSame([
            'defaultRequired' => true,
            'data' => null,
            'names' => [
                'email' => 'Email',
                'name' => 'Name',
            ],
            'rules' => [
                'email' => [
                    'email' => [],
                ],
                'name' => [
                    'length' => [
                        'max' => 1,
                        'countByChars' => true,
                    ],
                ],
            ],
            'messages' => [
                'email' => [],
                'name' => [],
            ],
            'fields' => [],
        ], $v->getOptions());
    }

    public function testAddRuleWithKeyAndLabel()
    {
        $v = V::new();
        $v->char('name', 'The name', 2);
        $ret = $v->check([
            'name' => '1',
        ]);
        $this->assertRetErr($ret, 'The name must be at least 2 character(s)');
    }

    public function testAddRuleInvalidArgument()
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException('Expected at least 2 arguments for rule, but got 0')
        );
        $v = V::new();
        $v->char();
    }

    public function testAddRuleInvalidArgument2()
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException('Expected at least 2 arguments for rule, but got 1')
        );
        $v = V::new();
        $v->char('name');
    }

    public function testAddRuleWithEmailValidator()
    {
        $v = V::new();
        $v->email('email', 'Your email');
        $ret = $v->check([
            'email' => 'test',
        ]);

        $this->assertRetErr($ret, 'Your email must be valid email address');
    }

    public function testAddRuleWithAssociativeArrayOptions()
    {
        $v = V::new();
        $v->key('name')->maxLength([
            'max' => 1,
            'countByChars' => false,
        ]);
        $ret = $v->check([
            'name' => '我',
        ]);
        $this->assertRetErr($ret, 'This value must have a length lower than 1');

        $v = V::new();
        $v->key('name')->maxLength([
            'max' => 1,
            'countByChars' => true,
        ]);
        $ret = $v->check([
            'name' => '我',
        ]);
        $this->assertRetSuc($ret);
    }

    /**
     * @dataProvider providerForAllow
     * @param mixed $value
     * @param mixed $allow
     */
    public function testAllow($value, $allow, bool $result)
    {
        $v = V::new();
        $v->mobileCn('key', 'label')->allow($allow);
        $ret = $v->check([
            'key' => $value,
        ]);
        $this->assertSame($result, $ret->isSuc());
    }

    public function providerForAllow()
    {
        return [
            ['13800138000', '', true],
            ['', '', true],
            ['', null, false],
            [null, null, true],
            [[], [], true],
            ['', [], false],
        ];
    }

    public function testAllowMultiple()
    {
        $v = V::new();
        $v->mobileCn('key', 'label')->allow([], '');

        $ret = $v->check([
            'key' => [],
        ]);
        $this->assertRetSuc($ret);

        $ret = $v->check([
            'key' => '',
        ]);
        $this->assertRetSuc($ret);

        $ret = $v->check([
            'key' => null,
        ]);
        $this->assertRetErr($ret, 'label must be valid mobile number');
    }

    public function testAllowEmptyWithEmptyString()
    {
        $ret = V::allowEmpty()->email()->check('');
        $this->assertRetSuc($ret);
    }

    public function testAllowEmptyWithNull()
    {
        $ret = V::allowEmpty()->email()->check(null);
        $this->assertRetSuc($ret);
    }

    public function testAllowEmptyWithValidData()
    {
        $ret = V::email()->check('test@example.com');
        $this->assertRetSuc($ret);

        $ret = V::allowEmpty()->email()->check('test@example.com');
        $this->assertRetSuc($ret);
    }

    public function testSkipArray()
    {
        $v = V::new();
        $v->key('email')->allowEmpty()->email();
        $v->time('send_at', 'Send time')->allowEmpty()->gt('2020-01-01');
        $ret = $v->check([
            'email' => '',
            'send_at' => null,
        ]);
        $this->assertRetSuc($ret);
    }

    public function testGetValidDataSuc()
    {
        $v = V::new();
        $v->key('email')->email();
        $v->key('name')->minLength(1);
        $ret = $v->check([
            'email' => 'test@email.com',
            'name' => '123',
            'notChecked' => true,
        ]);
        $this->assertSame([
            'email' => 'test@email.com',
            'name' => '123',
        ], $ret['data']);
    }

    public function testGetValidDataErr()
    {
        $v = V::new();
        $v->key('email')->email();
        $v->key('name')->minLength(1);

        $ret = $v->check([
            'email' => 'test@email.com',
            'name' => '',
            'notChecked' => true,
        ]);

        $this->assertRetErr($ret, 'This value must have a length greater than 1');
        $this->assertArrayNotHasKey('data', $ret);
    }

    public function testGetValidDataWithArrayKey()
    {
        $v = V::new();
        $v->key(['user', 'email'], '测试')->email();
        $ret = $v->check([
            'notChecked' => true,
            'user' => [
                'email' => 'test@example.com',
                'notChecked' => true,
            ],
        ]);

        $this->assertSame([
            'user' => [
                'email' => 'test@example.com',
            ],
        ], $ret['data']);
    }

    public function testGetValidDataWithChildren()
    {
        $v = V::new();
        $v->key('configs')->children(call_user_func(function () {
            $v = V::new();
            $v->key('key1', 'name1')->minLength(3);
            $v->key('key2', 'name2')->minLength(2);
            return $v;
        }));
        $ret = $v->check([
            'notChecked' => true,
            'configs' => [
                'notChecked' => true,
                'key1' => '123',
                'key2' => '22',
            ],
        ]);
        $this->assertSame([
            'configs' => [
                'key1' => '123',
                'key2' => '22',
            ],
        ], $ret['data']);
    }

    public function testGetValidDataWithEach()
    {
        $v = V::new();
        $v->key('products', 'Products')->each(function (V $v) {
            $v->key('name', 'Name')->maxLength(5);
            $v->key('stock', 'Stock')->greaterThanOrEqual(0);
        });
        $ret = $v->check([
            'notChecked' => true,
            'products' => [
                [
                    'name' => 'name',
                    'stock' => 1,
                    'notChecked' => true,
                ],
                [
                    'name' => 'name',
                    'stock' => 1,
                ],
            ],
        ]);
        $this->assertRetSuc($ret);

        $this->assertSame([
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
        ], $ret['data']);

        $this->assertRetSuc($ret);
    }

    public function testGetValidDataWithEachKeepKeys()
    {
        $v = V::new();
        $v->key('products')->each(function (V $v) {
            $v->key('name')->maxLength(5);
            $v->key('stock')->greaterThanOrEqual(0);
        });
        $ret = $v->check([
            'notChecked' => true,
            'products' => [
                'key1' => [
                    'name' => 'name',
                    'stock' => 1,
                    'notChecked' => true,
                ],
                'key2' => [
                    'name' => 'name',
                    'stock' => 1,
                ],
            ],
        ]);
        $this->assertSame([
            'products' => [
                'key1' => [
                    'name' => 'name',
                    'stock' => 1,
                ],
                'key2' => [
                    'name' => 'name',
                    'stock' => 1,
                ],
            ],
        ], $ret['data']);

        $this->assertRetSuc($ret);
    }

    public function testGetValidDataWithEachAndOtherRule()
    {
        $v = V::new();
        $v->key('products')->each(function (V $v) {
            $v->key('name')->maxLength(5);
            $v->key('stock')->greaterThanOrEqual(0);
            // last rule call `addValidData` overwrite previous rules
        })->minLength(1);
        $ret = $v->check([
            'notChecked' => true,
            'products' => [
                [
                    'name' => 'name',
                    'stock' => 1,
                    'notChecked' => true,
                ],
                [
                    'name' => 'name',
                    'stock' => 1,
                ],
            ],
        ]);
        $this->assertSame([
            'products' => [
                [
                    'name' => 'name',
                    'stock' => 1,
                    'notChecked' => true,
                ],
                [
                    'name' => 'name',
                    'stock' => 1,
                ],
            ],
        ], $ret['data']);

        $this->assertRetSuc($ret);
    }

    public function testGetValidDataExcludeOptionalKey()
    {
        $v = V::new();
        $v->key('email')->email()->optional();
        $v->key('name')->minLength(1);
        $ret = $v->check([
            'name' => '123',
            'notChecked' => true,
        ]);
        $this->assertSame([
            'name' => '123',
        ], $ret['data']);
    }

    public function testCheckOneSuc()
    {
        $ret = V::label('Your mobile')->mobileCn()->check('13800138000');
        $this->assertRetSuc($ret);
    }

    public function testCheckOneErr()
    {
        $ret = V::label('Your mobile')->mobileCn()->check('test');
        $this->assertRetErr($ret, 'Your mobile must be valid mobile number');
    }

    public function testCheckOneWithCallback()
    {
        $ret = V::callback(function ($name) {
            return 'twin' !== $name;
        })
            ->check('twin');
        $this->assertRetErr($ret, 'This value is not valid', -1);

        $ret = V::callback(function ($name) {
            return 'twin' !== $name;
        })
            ->check('hi');
        $this->assertRetSuc($ret);
    }

    public function testCheckObjectSuc()
    {
        $v = V::new();
        $v->key('email')->email();
        $v->key('name')->minLength(1);
        $ret = $v->check([
            'email' => 'test@email.com',
            'name' => '123',
        ]);
        $this->assertRetSuc($ret);
    }

    public function testCheckObjectErr()
    {
        $v = V::new();
        $v->email('email', 'This email');
        $v->minLength('name', 'This name', 3);
        $ret = $v->check([
            'email' => 'test',
            'name' => '1',
        ]);
        $this->assertRetErr($ret, 'This email must be valid email address');

        $ret = $v->check([
            'email' => 'test@example.com',
            'name' => '1',
        ]);
        $this->assertRetErr($ret, 'This name must have a length greater than 3');
    }

    public function testCheckNestedObjectSuc()
    {
        $v = V::new();
        $v->email(['user', 'email'], 'The email');
        $v->minLength('name', 'This name', 3);
        $ret = $v->check([
            'user' => [
                'email' => 'test@example.com',
            ],
            'name' => 'test',
        ]);
        $this->assertRetSuc($ret);
    }

    public function testCheckNestedObjectErr()
    {
        $v = V::new();
        $v->email(['user', 'email'], 'The email');
        $v->minLength('name', 'This name', 3);

        $ret = $v->check([
            'user' => [
                'email' => 'test',
            ],
            'name' => 'test',
        ]);
        $this->assertRetErr($ret, 'The email must be valid email address');
    }

    public function testCheckDeepNestedObjectSuc()
    {
        $v = V::new();
        $v->email(['user', 'primary', 'email'], 'The email');
        $v->minLength('name', 'This name', 3);

        $ret = $v->check([
            'user' => [
                'primary' => [
                    'email' => 'test@example.com',
                ],
            ],
            'name' => 'test',
        ]);
        $this->assertRetSuc($ret);
    }

    public function testCheckDeepNestedObjectErr()
    {
        $v = V::new();
        $v->email(['user', 'primary', 'email'], 'The email');
        $v->minLength('name', 'This name', 3);

        $ret = $v->check([
            'user' => [
                'primary' => [
                    'email' => 'test',
                ],
            ],
            'name' => 'test',
        ]);
        $this->assertRetErr($ret, 'The email must be valid email address');

        $ret = $v->check([
            'user' => [
                'primary' => 'test',
            ],
            'name' => 'test',
        ]);
        $this->assertRetErr($ret, 'The email is required');
    }

    public function testCheckBeforeModelCreate()
    {
        $ret = $this->checkModel(true, []);
        $this->assertRetErr($ret, 'Name is required');

        $ret = $this->checkModel(true, ['name' => '']);
        $this->assertRetErr($ret, 'Name must not be blank');

        $ret = $this->checkModel(true, ['name' => 'test']);
        $this->assertRetSuc($ret);
    }

    public function testCheckBeforeModelUpdate()
    {
        $ret = $this->checkModel(false, []);
        $this->assertRetSuc($ret);

        $ret = $this->checkModel(false, ['name' => '']);
        $this->assertRetErr($ret, 'Name must not be blank');

        $ret = $this->checkModel(false, ['name' => 'test']);
        $this->assertRetSuc($ret);
    }

    public function testHasFieldError()
    {
        $v = V::defaultOptional();
        $v->mediumText(['detail', 'content'], 'Content');
        $ret = $v->check(wei()->req);

        $this->assertNotSame("Content's length could not be detected", $ret['message']);
        $this->assertRetSuc($ret);
    }

    public function testDefaultNotEmpty()
    {
        $v = V::defaultNotEmpty();
        $v->tinyChar('name', 'Name');

        $ret = $v->check([
            'name' => '',
        ]);
        $this->assertRetErr($ret, 'Name must not be empty');
    }

    protected function checkModel(bool $isNew, $data)
    {
        $v = V::new();
        $v->key('name', 'Name')->required($isNew)->notBlank();
        return $v->check($data);
    }
}
