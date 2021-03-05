<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsRequiredTest extends TestCase
{
    public function testCallWithoutValidator()
    {
        $this->expectExceptionObject(
            new \LogicException('The "required" validator should not call directly, please use with \Wei\V')
        );
        $this->isRequired('test');
    }
}
