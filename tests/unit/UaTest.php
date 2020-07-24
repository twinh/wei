<?php

namespace WeiTest;

/**
 * @internal
 */
final class UaTest extends TestCase
{
    /**
     * @dataProvider providerForUserAgent
     * @param mixed $userAgent
     * @param mixed $os
     * @param mixed|null $version
     */
    public function testBrowser($userAgent, $os, $version = null)
    {
        $this->ua->setOption('userAgent', $userAgent);

        // Compatible for old tests
        if (is_string($os)) {
            $this->assertTrue($this->ua($os));
            $this->assertTrue($this->ua->{'is' . $os}());
            $this->assertEquals($version, $this->ua->getVersion($os));
        } else {
            foreach ($os as $value) {
                $this->assertTrue($this->ua($value[0]));
                $this->assertTrue($this->ua->{'is' . $value[0]}());
                $this->assertEquals($value[1], $this->ua->getVersion($value[0]));
            }
        }
    }

    public function testInvalidException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unrecognized browser, OS, mobile or tablet name "unknown"'
        );

        $this->ua->is('unknown');
    }

    public function testInvalidException2()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unrecognized browser, OS, mobile or tablet name "unknown"'
        );

        $this->ua->getVersion('unknown');
    }

    public function testGetVersion()
    {
        $result = $this->ua->getVersion('iphone');

        $this->assertFalse($result);
    }

    public function testNotIn()
    {
        $ua = new \Wei\Ua([
            'wei' => $this->wei,
            'server' => [
                'HTTP_USER_AGENT' => 'test',
            ],
        ]);

        $this->assertFalse($ua->isIPad());
    }

    public function testMagicCall()
    {
        $result = $this->ua->ua('iPad');
        $this->assertFalse($result);
    }

    /**
     * @link http://www.useragentstring.com/
     */
    public function providerForUserAgent()
    {
        return [
            // iPad
            [
                'Mozilla/5.0 (iPad; CPU OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
                [
                    ['ipad', '5_0'],
                    ['ios', '5_0'],
                ],
            ],
            [
                'Mozilla/5.0 (iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10',
                [
                    ['ipad', '3_2'],
                    ['ios', '3_2'],
                ],
            ],
            [
                'Mozilla/5.0 (iPad; CPU OS 4_3_2 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5',
                [
                    ['ipad', '4_3_2'],
                    ['ios', '4_3_2'],
                ],
            ],
            [
                'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25',
                [
                    ['ipad', '6_0'],
                    ['ios', '6_0'],
                ],
            ],
            [
                'Mozilla/5.0 (iPad; CPU OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
                [
                    ['ipad', '5_0'],
                    ['ios', '5_0'],
                ],
            ],
            // iPhone
            [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25',
                [
                    ['iphone', '6_0'],
                    ['ios', '6_0'],
                ],
            ],
            [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
                'iphone',
                '5_0',
            ],
            [
                'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_2 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5',
                'iphone',
                '4_3_2',
            ],
            [
                'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7',
                'iphone',
                '4_0',
            ],
            [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
                'iphone',
                '5_0',
            ],
            // iPod
            [
                'Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; ja-jp) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5',
                [
                    ['iphone', '4_3_3'],
                    ['ios', '4_3_3'],
                ],
            ],
            // Android
            [
                'Mozilla/5.0 (Linux; Android 4.1.2; Nexus 7 Build/JZ054K) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Safari/535.19',
                'android',
                '4.1.2',
            ],
            [
                'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19',
                'android',
                '4.0.4',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 4.0.2; en-us; Galaxy Nexus Build/ICL53F) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
                'android',
                '4.0.2',
            ],
            [
                'Mozilla/5.0 (Linux; U; Android 2.3.6; en-us; Nexus S Build/GRK39F) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                'android',
                '2.3.6',
            ],
            // Windows phone
            // @link http://blogs.windows.com/windows_phone/b/wpdev/archive/2012/10/17/getting-websites-ready-for-internet-explorer-10-on-windows-phone-8.aspx
            // @link http://www.developer.nokia.com/Community/Wiki/User-Agent_headers_for_Nokia_devices
            [
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 920)',
                [
                    ['ie', '10.0'],
                    ['windowsphone', '8.0'],
                ],
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 820)',
                [
                    ['ie', '10.0'],
                    ['windowsphone', '8.0'],
                ],
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; SAMSUNG; SGH-i917)',
                [
                    ['ie', '9.0'],
                    ['windowsphone', '7.5'],
                ],
            ],
            [
                'Mozilla/4.0 (compatible; MSIE 7.0; Windows Phone OS 7.0; Trident/3.1; IEMobile/7.0; LG; GW910)',
                [
                    ['ie', '7.0'],
                    ['windowsphone', '7.0'],
                ],
            ],
            // Chrome
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.60 Safari/537.17',
                'chrome',
                '24.0.1312.60',
            ],
            [
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1309.0 Safari/537.17',
                'chrome',
                '24.0.1309.0',
            ],
            [
                'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4',
                'chrome',
                '22.0.1229.94',
            ],
            // Firefox
            [
                'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0',
                'firefox',
                '20.0',
            ],
            [
                'Mozilla/6.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1',
                'firefox',
                '16.0.1',
            ],
            [
                'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1',
                'firefox',
                '15.0.1',
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:14.0) Gecko/20120405 Firefox/14.0a1',
                'firefox',
                '14.0a1',
            ],
            // IE
            [
                'Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0',
                'ie',
                '10.6',
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)',
                'ie',
                '10.0',
            ],
            [
                'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))',
                'ie',
                '9.0',
            ],
            [
                'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.2; SV1; .NET CLR 3.3.69573; WOW64; en-US)',
                'ie',
                '8.0',
            ],
            [
                'Mozilla/5.0 (MSIE 7.0; Macintosh; U; SunOS; X11; gu; SV1; InfoPath.2; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)',
                'ie',
                '7.0',
            ],
            [
                'Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)',
                'ie',
                '6.0',
            ],
        ];
    }

    public function testNotInMobile()
    {
        $ua = new \Wei\Ua([
            'wei' => $this->wei,
            'server' => [
                'HTTP_USER_AGENT' => 'test',
            ],
        ]);
        $this->assertFalse($ua->isMobile());
    }

    /**
     * @dataProvider providerForInMobile
     * @param mixed $servers
     */
    public function testInMobile($servers)
    {
        $ua = new \Wei\Ua([
            'wei' => $this->wei,
            'server' => $servers,
        ]);
        $this->assertTrue($ua->isMobile());
    }

    public function providerForInMobile()
    {
        return [
            [
                [
                    'HTTP_ACCEPT' => 'application/x-obml2d',
                ],
            ],
            [
                [
                    'HTTP_ACCEPT' => 'text/vnd.wap.wml',
                ],
            ],
            [
                [
                    'HTTP_ACCEPT' => 'application/vnd.wap.xhtml+xml',
                ],
            ],
            [
                [
                    'HTTP_X_WAP_PROFILE' => '1',
                ],
            ],
            [
                [
                    'HTTP_X_WAP_CLIENTID' => '1',
                ],
            ],
            [
                [
                    'HTTP_WAP_CONNECTION' => '1',
                ],
            ],
            [
                [
                    'HTTP_PROFILE' => '1',
                ],
            ],
            [
                [
                    'HTTP_X_OPERAMINI_PHONE_UA' => '1',
                ],
            ],
            [
                [
                    'HTTP_X_NOKIA_IPADDRESS' => '1',
                ],
            ],
            // 10
            [
                [
                    'HTTP_X_NOKIA_GATEWAY_ID' => '1',
                ],
            ],
            [
                [
                    'HTTP_X_ORANGE_ID' => '1',
                ],
            ],
            [
                [
                    'HTTP_X_VODAFONE_3GPDPCONTEXT' => '1',
                ],
            ],
            [
                [
                    'HTTP_X_HUAWEI_USERID' => '1',
                ],
            ],
            [
                [
                    'HTTP_UA_OS' => '1',
                ],
            ],
            [
                [
                    'HTTP_X_MOBILE_GATEWAY' => '1',
                ],
            ],
            [
                [
                    'HTTP_X_ATT_DEVICEID' => '1',
                ],
            ],
            [
                [
                    'HTTP_UA_CPU' => 'ARM',
                ],
            ],
        ];
    }
}
