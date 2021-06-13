<?php

namespace WeiTest;

use Wei\Logger;
use Wei\Ret;
use Wei\RetTrait;

/**
 * @internal
 */
final class RetTest extends TestCase
{
    use RetTrait;

    public function testSuc()
    {
        $ret = Ret::suc();
        $this->assertSame([
            'message' => $ret['message'],
            'code' => 0,
        ], $ret->toArray());
    }

    public function testErr()
    {
        $ret = Ret::err('Operation failed');
        $this->assertSame([
            'message' => 'Operation failed',
            'code' => -1,
        ], $ret->toArray());
    }

    public function testSucWithCustomMessage()
    {
        $ret = Ret::suc('success');
        $this->assertSame([
            'message' => 'success',
            'code' => 0,
        ], $ret->toArray());
    }

    public function testSucWithArray()
    {
        $ret = Ret::suc([
            'message' => 'Payment successful',
            'amount' => '10.00',
            'data' => [
                'key' => 'value',
            ],
        ]);
        $this->assertSame([
            'message' => 'Payment successful',
            'amount' => '10.00',
            'data' => [
                'key' => 'value',
            ],
            'code' => 0,
        ], $ret->toArray());
    }

    public function testErrWithArray()
    {
        $ret = Ret::err([
            'message' => 'Payment failed',
            'amount' => '10.00',
            'data' => [
                'key' => 'value',
            ],
        ]);
        $this->assertSame([
            'message' => 'Payment failed',
            'amount' => '10.00',
            'data' => [
                'key' => 'value',
            ],
            'code' => -1,
        ], $ret->toArray());
    }

    public function testSucWithMessage()
    {
        $ret = Ret::suc('操作成功');
        $this->assertSame([
            'message' => '操作成功',
            'code' => 0,
        ], $ret->toArray());
    }

    public function testSucWithFormat()
    {
        $ret = Ret::suc(['me%sag%s', 'ss', 'e']);
        $this->assertSame('message', $ret['message']);
    }

    public function testErrWithFormat()
    {
        $ret = Ret::err(['me%sage', 'ss'], 2);
        $this->assertSame([
            'message' => 'message',
            'code' => 2,
        ], $ret->toArray());
    }

    public function testInvalidArgument()
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException('Expected argument of type string or array, "boolean" given')
        );

        Ret::suc(false);
    }

    public function testIsErr()
    {
        $ret = Ret::suc();
        $this->assertFalse($ret->isErr());

        $ret = Ret::err('error');
        $this->assertTrue($ret->isErr());
    }

    public function testIsSuc()
    {
        $ret = Ret::suc();
        $this->assertTrue($ret->isSuc());

        $ret = Ret::err('error');
        $this->assertFalse($ret->isSuc());
    }

    public function testRetSuc()
    {
        $ret = Ret::suc('suc');
        $this->assertSame([
            'message' => 'suc',
            'code' => 0,
        ], $ret->toArray());
    }

    public function testRetErr()
    {
        $ret = Ret::err('err', 0);
        $this->assertSame([
            'message' => 'err',
            'code' => 0,
        ], $ret->toArray());
    }

    public function testCreateNewInstance()
    {
        $ret1 = Ret::suc();
        $ret2 = Ret::suc();

        $this->assertEquals($ret1, $ret2);
        $this->assertNotSame($ret1, $ret2);
    }

    public function testGetMetadata()
    {
        $ret = Ret::suc();

        $ret->setMetadata('key', 'value');
        $ret->setMetadata('key2', 'value2');

        $this->assertSame('value', $ret->getMetadata('key'));
        $this->assertSame(['key' => 'value', 'key2' => 'value2'], $ret->getMetadata());
    }

    public function testSetMetadata()
    {
        $ret = Ret::suc();

        $ret->setMetadata('key', 'value');
        $this->assertSame('value', $ret->getMetadata('key'));

        $ret->setMetadata(['key2' => 'value2']);
        $this->assertSame(['key2' => 'value2'], $ret->getMetadata());
    }

    public function testRemoveMetadata()
    {
        $ret = Ret::suc();

        $ret->setMetadata('key', 'value');
        $ret->setMetadata('key2', 'value2');

        $ret->removeMetadata('key');
        $this->assertSame(['key2' => 'value2'], $ret->getMetadata());

        $ret->removeMetadata();
        $this->assertSame([], $ret->getMetadata());
    }

    public function testWarning()
    {
        $logger = $this->createMock(Logger::class);

        $logger->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo('warning'),
                $this->equalTo('Warning!')
            );

        $ret = new Ret([
            'wei' => $this->wei,
            'logger' => $logger,
        ]);

        $ret->warning('Warning!');
        $this->assertSame([
            'message' => 'Warning!',
            'code' => -1,
        ], $ret->toArray());
    }

    public function testAlert()
    {
        $logger = $this->createMock(Logger::class);

        $logger->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo('alert'),
                $this->equalTo('Alert!')
            );

        $ret = new Ret([
            'wei' => $this->wei,
            'logger' => $logger,
        ]);

        $ret->alert('Alert!');
        $this->assertSame([
            'message' => 'Alert!',
            'code' => -1,
        ], $ret->toArray());
    }

    public function testWithArray()
    {
        $ret = Ret::suc()->with(['key' => 'value']);
        $this->assertSame([
            'message' => $ret['message'],
            'code' => 0,
            'key' => 'value',
        ], $ret->toArray());
    }

    public function testWithKeyValue()
    {
        $ret = Ret::suc()->with('key', 'value');
        $this->assertSame([
            'message' => $ret['message'],
            'code' => 0,
            'key' => 'value',
        ], $ret->toArray());
    }

    public function testWithOverwrite()
    {
        $ret = Ret::suc()->with('message', 'value');
        $this->assertSame([
            'message' => 'value',
            'code' => 0,
        ], $ret->toArray());
    }

    public function testDataArray()
    {
        $ret = Ret::suc()->data(['key' => 'value']);
        $this->assertSame([
            'message' => $ret['message'],
            'code' => 0,
            'data' => [
                'key' => 'value',
            ],
        ], $ret->toArray());
    }

    public function testDataKeyValue()
    {
        $ret = Ret::suc()->data('key', 'value');
        $this->assertSame([
            'message' => $ret['message'],
            'code' => 0,
            'data' => [
                'key' => 'value',
            ],
        ], $ret->toArray());
    }

    public function testDataOverwrite()
    {
        $ret = Ret::suc(['data' => ['key' => 'foo']])->data('key', 'value');
        $this->assertSame([
            'data' => [
                'key' => 'value',
            ],
            'message' => $ret['message'],
            'code' => 0,
        ], $ret->toArray());
    }
}
