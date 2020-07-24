<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class CallbackTest extends TestCase
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

    public function arrayCallback()
    {
        return false;
    }
}
