<?php

namespace WeiTest;

use Wei\IsCallback;
use Wei\V;
use Wei\Validate;

/**
 * @mixin \IsCallbackMixin
 * @internal
 */
final class IsCallbackTest extends BaseValidatorTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->inputTestOptions['fn'] = static function () {
        };
    }

    public function testCallback()
    {
        $this->assertTrue($this->isCallback('data', static function () {
            return true;
        }));
    }

    public function testNotCallback()
    {
        $this->assertFalse($this->isCallback('data', static function () {
            return false;
        }));

        $this->assertFalse($this->isCallback('data', static function () {
            // convert to boolen(false)
            return null;
        }));
    }

    public function testCallbackMessage()
    {
        $this->isCallback('data', static function () {
            return false;
        }, 'invalid message');
        $this->assertEquals('invalid message', $this->isCallback->getFirstMessage());
    }

    public function testArrayCallback()
    {
        $result = $this->isCallback('data', [$this, 'arrayCallback']);

        $this->assertFalse($result);
    }

    public function testGetValidator()
    {
        $validator = wei()->isCallback->getValidator();
        $this->assertNull($validator);
    }

    public function testGetValidatorFromV()
    {
        $validator = null;
        $v = V::new();
        $v->key('test')->callback(static function ($input, IsCallback $callback) use (&$validator) {
            $validator = $callback->getValidator();
            return false;
        });
        $v->check([
            'test' => 1,
        ]);
        $this->assertInstanceOf(Validate::class, $validator);
    }

    public function arrayCallback()
    {
        return false;
    }

    public function testReturnMessage()
    {
        $this->isCallback('data', static function () {
            return 'invalid message';
        });
        $this->assertEquals('invalid message', $this->isCallback->getFirstMessage());
    }
}
