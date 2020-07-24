<?php

namespace WeiTest;

use Wei\Validate;

/**
 * @internal
 */
final class VTest extends TestCase
{
    public function testCheckFail()
    {
        $ret = wei()->v()
            ->key('question', 'Question')
            ->check([]);

        $this->assertRetErr($ret, -1, 'Question is required');
    }

    public function testCheckPass()
    {
        $ret = wei()->v()
            ->key('question', 'Question')
            ->check([
                'question' => '1',
            ]);

        $this->assertRetSuc($ret);
    }

    public function testMessage()
    {
        $ret = wei()->v()
            ->key('name', '名称')->message('required', '请填写%name%')
            ->check([]);

        $this->assertRetErr($ret, -1, '请填写名称');
    }

    public function testMessageWithoutRule()
    {
        $ret = wei()->v()
            ->key('name', '名称')->required()->message('请填写%name%')
            ->check([]);

        $this->assertRetErr($ret, -1, '请填写名称');
    }

    public function testCallback()
    {
        $ret = wei()->v()
            ->key('name')->callback(function ($name) {
                return 'twin' !== $name;
            })
            ->check(['name' => 'twin']);
        $this->assertRetErr($ret, -1, 'This value is not valid');

        $ret = wei()->v()
            ->key('name')->callback(function ($name) {
                return 'twin' !== $name;
            })
            ->check(['name' => 'hi']);
        $this->assertRetSuc($ret);
    }

    public function testMobileCn()
    {
        $ret = wei()->v()
            ->key('mobile', 'Mobile')->mobileCn()
            ->check([
                'mobile' => '123',
            ]);
        $this->assertRetErr($ret, -1, 'Mobile must be valid mobile number');
    }

    public function testValidate()
    {
        $validator = wei()->v()
            ->key('mobile')->mobileCn()
            ->validate(['mobile' => '123']);

        $this->assertInstanceOf(Validate::class, $validator);
        $this->assertFalse($validator->isValid());
    }

    public function testIsValid()
    {
        $result = wei()->v()
            ->key('mobile')->mobileCn()
            ->isValid(['mobile' => '123']);

        $this->assertFalse($result);
    }

    public function testWithoutKeyRetErr()
    {
        $ret = wei()->v()
            ->mobileCn()
            ->label('Mobile')
            ->check('123');

        $this->assertRetErr($ret, -1, 'Mobile must be valid mobile number');
    }

    public function testWithoutKeyRetSuc()
    {
        $ret = wei()->v()
            ->mobileCn()
            ->label('Mobile')
            ->check('13800138000');

        $this->assertRetSuc($ret);
    }

    public function testWithoutKeyValidate()
    {
        $validator = wei()->v()
            ->digit()
            ->between(1, 150)
            ->label('Age')
            ->validate('ab');
        $this->assertFalse($validator->isValid());

        $messages = $validator->getSummaryMessages();
        $this->assertEquals('Age must contain only digits (0-9)', $messages[''][0]);
        $this->assertEquals('Age must between 1 and 150', $messages[''][1]);
    }
}
