<?php

namespace WeiTest;

use Wei\IsCallback;
use Wei\V;
use Wei\Validate;

/**
 * @internal
 */
final class IsCallbackTest extends BaseValidatorTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->inputTestOptions['fn'] = function () {
        };
    }

    public function testCallback()
    {
        $this->assertTrue($this->isCallback('data', function () {
            return true;
        }));
    }

    public function testNotCallback()
    {
        $this->assertFalse($this->isCallback('data', function () {
            return false;
        }));

        $this->assertFalse($this->isCallback('data', function () {
            // convert to boolen(false)
            return null;
        }));
    }

    public function testCallbackMessage()
    {
        $this->isCallback('data', function () {
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
        V
            ::key('test')->callback(function ($input, IsCallback $callback) use (&$validator) {
                $validator = $callback->getValidator();
                return false;
            })
                ->check([
                'test' => 1,
            ]);
        $this->assertInstanceOf(Validate::class, $validator);
    }

    public function arrayCallback()
    {
        return false;
    }
}
