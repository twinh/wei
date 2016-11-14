<?php

namespace WeiTest\Validator;

class IpTest extends TestCase
{
    /**
     * @dataProvider providerForIp
     */
    public function testIp($input, $options = null)
    {
        $this->assertTrue($this->isIp($input, $options));
    }

    /**
     * @dataProvider providerForNotIp
     */
    public function testNotIp($input, $options = null)
    {
        $this->assertFalse($this->isIp($input, $options));
    }

    public function providerForIp()
    {
        return array(
            array('192.168.0.1'),
            array('10.0.0.0'),
            array('172.16.0.0'),
            array('192.168.0.0'),
            array('0.0.0.0'),
            array('169.254.0.0'),
            array('192.0.2.0'),
            array('224.0.0.0'),
            array('255.255.255.255', array(
                'ipv4' => true
            )),
            array('2001:0db8:85a3:08d3:1319:8a2e:0370:7334', array(
                'ipv6' => true,
            )),
            array('0:0:0:0:0:0:0:0', array(
                'ipv6' => true,
            )),
            array('::', array(
                'ipv6' => true,
            )),
            array('0::', array(
                'ipv6' => true,
            )),
            array('::0', array(
                'ipv6' => true,
            )),
            array('0::0', array(
                'ipv6' => true,
            )),
        );
    }

    public function providerForNotIp()
    {
        return array(
            array('192.168.0.1', array(
                'noPrivRange' => true
            )),
            array('10.0.0.0', array(
                'noPrivRange' => true,
            )),
            array('172.16.0.0', array(
                'noPrivRange' => true,
            )),
            array('192.168.0.0', array(
                'noPrivRange' => true,
            )),
            array('0.0.0.0', array(
                'noResRange' => true,
            )),
            array('169.254.0.0', array(
                'noResRange' => true,
            )),
            array('255.255.255.255', array(
                'ipv6' => true
            )),
            array('2001:0db8:85a3:08d3:1319:8a2e:0370:7334', array(
                'ipv4' => true,
            )),
        );
    }
}
