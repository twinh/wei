<?php

namespace WeiTest;

use Wei\Base;
use Wei\Logger;
use Wei\Req;
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

    public function testSucMessageWithFormat()
    {
        $ret = Ret::suc([
            'message' => ['me%sage', 'ss'],
        ]);
        $this->assertSame('message', $ret['message']);
    }

    public function testErrMessageWithFormat()
    {
        $ret = Ret::err([
            'message' => ['me%sage', 'ss'],
            'code' => 2,
        ]);
        $this->assertSame('message', $ret['message']);
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

    public function testTransform()
    {
        $ret = Ret::suc(['data' => ['id' => 1, 'password' => 2]]);

        $ret->transform(new class () extends Base {
            /**
             * @svc
             * @param mixed $data
             */
            protected function toArray($data): array
            {
                return [
                    'data' => [
                        'id' => $data['id'],
                    ],
                ];
            }
        });

        $this->assertSame(['id' => 1], $ret['data']);
    }

    public function testTransformWithMoreData()
    {
        $ret = Ret::suc(['data' => ['id' => 1, 'password' => 2]]);

        $ret->transform(new class () extends Base {
            /**
             * @svc
             * @param mixed $data
             */
            protected function toArray($data): array
            {
                return [
                    'custom' => true,
                    'data' => [
                        'id' => $data['id'],
                    ],
                ];
            }
        });

        $this->assertSame(['id' => 1], $ret['data']);
        $this->assertTrue($ret['custom']);
    }

    public function testTransformWithoutData()
    {
        $ret = Ret::err('err', 1);

        $ret->transform(new class () extends Base {
            /**
             * @svc
             */
            protected function toArray()
            {
                throw new \Exception('should not called');
            }
        });

        $this->assertArrayNotHasKey('data', $ret);
    }

    public function testTransformWithInvalidArgument()
    {
        $ret = Ret::suc(['data' => ['id' => 1, 'password' => 2]]);

        $this->expectExceptionObject(new \InvalidArgumentException(
            'Expected class `stdClass` to have method `toArray`'
        ));

        $ret->transform(\stdClass::class);
    }

    public function testInclude()
    {
        $req = new Req([
            'wei' => $this->wei,
            'fromGlobal' => false,
            'data' => [
                'include' => 'key,key2',
            ],
        ]);
        $ret = Ret::suc();
        $ret->req = $req;
        $ret->include('key', function () {
            return 'value';
        });
        $this->assertSame('value', $ret['data']['key']);
        $this->assertArrayNotHasKey('key2', $ret['data']);
    }

    public function testIncludeWith()
    {
        $req = new Req([
            'wei' => $this->wei,
            'fromGlobal' => false,
            'data' => [
                'includeWith' => ['key', 'key2'],
            ],
        ]);
        $ret = Ret::suc();
        $ret->req = $req;
        $ret->includeWith('key', function () {
            return 'value';
        });

        $this->assertSame('value', $ret['key']);
        $this->assertArrayNotHasKey('key2', $ret);
        $this->assertArrayNotHasKey('data', $ret);
    }

    public function testGetter()
    {
        $ret = Ret::suc('suc');
        $this->assertSame(0, $ret->getCode());
        $this->assertSame('suc', $ret->getMessage());
        $this->assertNull($ret->get('test'));
        $this->assertNull($ret->get('data'));

        $ret = Ret::err('err', 2);
        $this->assertSame(2, $ret->getCode());
        $this->assertSame('err', $ret->getMessage());
        $this->assertNull($ret->get('test'));
        $this->assertNull($ret->get('data'));
    }

    public function testSetter()
    {
        $ret = Ret::suc('suc');
        $ret->setCode(1);
        $ret->setMessage('test');
        $ret->setData('data');
        $ret->set('key', 'value');

        $this->assertSame(1, $ret->getCode());
        $this->assertSame('test', $ret->getMessage());
        $this->assertSame('data', $ret->getData());
        $this->assertSame('value', $ret->get('key'));
    }

    public function testToStringSuc()
    {
        $ret = Ret::suc('suc');
        $this->assertSame('{
    "message": "suc",
    "code": 0
}', (string) $ret);
    }

    public function testToStringErr()
    {
        $ret = Ret::err('err', 1);
        $this->assertSame('{
    "message": "err",
    "code": 1
}', (string) $ret);
    }

    public function testToStringWithData()
    {
        $ret = Ret::suc([
            'message' => 'ok',
            'data' => 'data',
        ]);
        $this->assertSame('{
    "message": "ok",
    "data": "data",
    "code": 0
}', (string) $ret);
    }
}
