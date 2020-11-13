<?php

namespace WeiTest;

use Wei\IsEmail;
use Wei\V;
use Wei\Validate;

/**
 * @internal
 */
final class VTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function testChainMode()
    {
        $ret = V::key('email')->email()
            ->key('name')->minLength(1)
            ->check([
                'email' => 'test@email.com',
                'name' => '123',
            ]);
        $this->assertRetSuc($ret);
    }

    public function testSplitMode()
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

    public function testCheckFail()
    {
        $ret = V::key('question', 'Question')
            ->check([]);

        $this->assertRetErr($ret, -1, 'Question is required');
    }

    public function testCheckPass()
    {
        $ret = V::key('question', 'Question')
            ->check([
                'question' => '1',
            ]);

        $this->assertRetSuc($ret);
    }

    public function testMessage()
    {
        $ret = V::key('name', '名称')->message('required', '请填写%name%')
            ->check([]);

        $this->assertRetErr($ret, -1, '请填写名称');
    }

    public function testMessageWithoutRule()
    {
        $ret = V::key('name', '名称')->required()->message('请填写%name%')
            ->check([]);

        $this->assertRetErr($ret, -1, '请填写名称');
    }

    public function testCallback()
    {
        $ret = V::key('name')->callback(function ($name) {
            return 'twin' !== $name;
        })
            ->check(['name' => 'twin']);
        $this->assertRetErr($ret, -1, 'This value is not valid');

        $ret = V::key('name')->callback(function ($name) {
            return 'twin' !== $name;
        })
            ->check(['name' => 'hi']);
        $this->assertRetSuc($ret);
    }

    public function testMobileCn()
    {
        $ret = V::key('mobile', 'Mobile')->mobileCn()
            ->check([
                'mobile' => '123',
            ]);
        $this->assertRetErr($ret, -1, 'Mobile must be valid mobile number');
    }

    public function testValidate()
    {
        $validator = V::key('mobile')->mobileCn()
            ->validate(['mobile' => '123']);

        $this->assertInstanceOf(Validate::class, $validator);
        $this->assertFalse($validator->isValid());
    }

    public function testIsValid()
    {
        $result = V::key('mobile')->mobileCn()
            ->isValid(['mobile' => '123']);

        $this->assertFalse($result);
    }

    public function testWithoutKeyRetErr()
    {
        $ret = V::label('Mobile')
            ->mobileCn()
            ->check('123');

        $this->assertRetErr($ret, -1, 'Mobile must be valid mobile number');
    }

    public function testWithoutKeyRetSuc()
    {
        $ret = V::label('Mobile')
            ->mobileCn()
            ->check('13800138000');

        $this->assertRetSuc($ret);
    }

    public function testWithoutKeyValidate()
    {
        $validator = V::label('Age')
            ->digit()
            ->between(1, 150)
            ->validate('ab');
        $this->assertFalse($validator->isValid());

        $messages = $validator->getSummaryMessages();
        $this->assertEquals('Age must contain only digits (0-9)', $messages[''][0]);
        $this->assertEquals('Age must between 1 and 150', $messages[''][1]);
    }

    public function testCreateNewInstance()
    {
        $this->assertNotSame(V::key('test'), V::key('test'));
    }

    public function testInvoke()
    {
        $this->assertNotSame(wei()->v(), wei()->v());
    }

    public function testCheckBeforeModelCreate()
    {
        $ret = $this->checkModel(true, []);
        $this->assertRetErr($ret, null, 'Name is required');

        $ret = $this->checkModel(true, ['name' => '']);
        $this->assertRetErr($ret, null, 'Name must not be blank');

        $ret = $this->checkModel(true, ['name' => 'test']);
        $this->assertRetSuc($ret);
    }

    public function testCheckBeforeModelUpdate()
    {
        $ret = $this->checkModel(false, []);
        $this->assertRetSuc($ret);

        $ret = $this->checkModel(false, ['name' => '']);
        $this->assertRetErr($ret, null, 'Name must not be blank');

        $ret = $this->checkModel(false, ['name' => 'test']);
        $this->assertRetSuc($ret);
    }

    public function testOptions()
    {
        $ret = V::key('name')->maxLength([
            'max' => 1,
            'countByChars' => false,
        ])->check([
            'name' => '我',
        ]);
        $this->assertRetErr($ret, null, 'This value must have a length lower than 1');

        $ret = V::key('name')->maxLength([
            'max' => 1,
            'countByChars' => true,
        ])->check([
            'name' => '我',
        ]);
        $this->assertRetSuc($ret);
    }

    public function testDefaultOptional()
    {
        $ret = V::defaultOptional()
            ->key('email')->email()
            ->check([]);
        $this->assertRetSuc($ret);
    }

    public function testDefaultRequired()
    {
        $ret = V::defaultRequired()
            ->key('email')->email()
            ->check([]);
        $this->assertRetErr($ret, null, 'This value is required');
    }

    public function testGetOptions()
    {
        $v = V::key('email', 'Email')->email()
            ->key('name', 'Name')->length(['max' => 1, 'countByChars' => true]);
        $this->assertSame([
            'data' => [],
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
            'names' => [
                'email' => 'Email',
                'name' => 'Name',
            ],
            'fields' => [],
        ], $v->getOptions());
    }

    public function testBasicType()
    {
        $ret = V::char('name', '名称', 2)->check([
            'name' => '1',
        ]);
        $this->assertRetErr($ret, null, '名称 must be at least 2 character(s)');
    }

    public function testBasicTypeChain()
    {
        $ret = V::char('name', '名称', 2)
            ->key('name2', '名称2')->addRule('char', 2)
            ->check([
                'name' => '12',
                'name2' => '1',
            ]);
        $this->assertRetErr($ret, null, '名称2 must be at least 2 character(s)');
    }

    public function testBasicTypeInvalidArgument()
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Expected at least 2 arguments for type rule, but got 0'));
        V::char();
    }

    public function testBasicTypeInvalidArgument2()
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Expected at least 2 arguments for type rule, but got 1'));
        V::char('name');
    }

    public function testArrayKey()
    {
        $ret = V::key(['user', 'email'], '测试')->email()
            ->check([
                'user' => [
                    'email' => 'test@example.com',
                ],
            ]);

        $this->assertRetSuc($ret);
    }

    public function testArrayKey2()
    {
        $ret = V::key(['user', 'primary', 'email'], '测试')->email()
            ->check([
                'user' => [
                    'primary' => [
                        'email' => 'test@example.com',
                    ],
                ],
            ]);

        $this->assertRetSuc($ret);
    }

    public function testArray()
    {
        $validator = V::key('name', 'Name')
            ->key(['user', 'sex'])->in(['array' => [0, 1, 2]])
            ->key(['user', 'email'], 'Email')->email()->message('Invalid %name%')
            ->validate([
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

        $this->assertSame(['user.email' => ['email' => 'Invalid %name%']], $validator->getMessages());

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

    protected function checkModel(bool $isNew, $data)
    {
        return V::key('name', 'Name')->required($isNew)->notBlank()
            ->check($data);
    }
}
