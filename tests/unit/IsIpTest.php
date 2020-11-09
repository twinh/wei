<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsIpTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForIp
     * @param mixed $input
     * @param mixed|null $options
     */
    public function testIp($input, $options = null)
    {
        $this->assertTrue($this->isIp($input, $options));
    }

    /**
     * @dataProvider providerForNotIp
     * @param mixed $input
     * @param mixed|null $options
     */
    public function testNotIp($input, $options = null)
    {
        $this->assertFalse($this->isIp($input, $options));
    }

    public function providerForIp()
    {
        return [
            ['192.168.0.1'],
            ['10.0.0.0'],
            ['172.16.0.0'],
            ['192.168.0.0'],
            ['0.0.0.0'],
            ['169.254.0.0'],
            ['192.0.2.0'],
            ['224.0.0.0'],
            ['255.255.255.255', [
                'ipv4' => true,
            ]],
            ['2001:0db8:85a3:08d3:1319:8a2e:0370:7334', [
                'ipv6' => true,
            ]],
            ['0:0:0:0:0:0:0:0', [
                'ipv6' => true,
            ]],
            ['::', [
                'ipv6' => true,
            ]],
            ['0::', [
                'ipv6' => true,
            ]],
            ['::0', [
                'ipv6' => true,
            ]],
            ['0::0', [
                'ipv6' => true,
            ]],
        ];
    }

    public function providerForNotIp()
    {
        return [
            ['192.168.0.1', [
                'noPrivRange' => true,
            ]],
            ['10.0.0.0', [
                'noPrivRange' => true,
            ]],
            ['172.16.0.0', [
                'noPrivRange' => true,
            ]],
            ['192.168.0.0', [
                'noPrivRange' => true,
            ]],
            ['0.0.0.0', [
                'noResRange' => true,
            ]],
            ['169.254.0.0', [
                'noResRange' => true,
            ]],
            ['255.255.255.255', [
                'ipv6' => true,
            ]],
            ['2001:0db8:85a3:08d3:1319:8a2e:0370:7334', [
                'ipv4' => true,
            ]],
        ];
    }
}
