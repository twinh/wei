<?php

namespace WeiTest;

class IsUNumberTest extends BaseValidatorTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function testUnsignedLessThanMessage()
    {
        $this->assertFalse($this->isUNumber(-1, 1, 0));
        $this->assertSame('This value must be greater than or equal to 0', $this->isUNumber->getFirstMessage());
    }
}
