<?php

namespace WeiTest\Validator;

class CallbackTest extends TestCase
{
    public function setUp()
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
