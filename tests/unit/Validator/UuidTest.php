<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class UuidTest extends TestCase
{
    /**
     * @dataProvider providerForUuid
     * @param mixed $input
     */
    public function testUuid($input)
    {
        $this->assertTrue($this->isUuid($input));
    }

    /**
     * @dataProvider providerForNotUuid
     * @param mixed $input
     */
    public function testNotUuid($input)
    {
        $this->assertFalse($this->isUuid($input));
    }

    public function providerForUuid()
    {
        return [
            ['550e8400-e29b-41d4-a716-446655440000'],
            ['12345678-1234-5678-1234-567812345678'],
            ['a8098c1a-f86e-11da-bd1a-00112444be1e'],
            ['16fd2706-8baf-433b-82eb-8c7fada847da'],
            ['886313e1-3b8a-5372-9b90-0c9aee199e5d'],
            ['00010203-0405-0607-0809-0a0b0c0d0e0f'],
            ['00010203-0405-0607-0809-0A0B0C0D0E0F'],
        ];
    }

    public function providerForNotUuid()
    {
        return [
            ['550e84001e29b241d43a7164446655440000'],
            ['00010203-0405-0607-0809-0A0B0C0D0E0+'],
            ['550e8400-e29b-41d4-a716-44665544000'],
            ['550e8400-e29b-41d4-a716-446655440中文'],
        ];
    }
}
