<?php

namespace WeiTest;

use Wei\V;
use Wei\Validate;

/**
 * @internal
 */
final class VTest extends TestCase
{
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
}
