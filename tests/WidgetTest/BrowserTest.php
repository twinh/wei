<?php

namespace WidgetTest;

class BrowserTest extends TestCase
{
    /**
     * @dataProvider providerForUserAgent
     */
    public function testBrowser($ua, $browser, $version)
    {
        $this->server->set('HTTP_USER_AGENT', $ua);
        $this->browser->detect();
        $this->assertEquals($version, $this->browser->version);
        $this->assertEquals($browser, $this->browser->name);
    }
    
    public function providerForUserAgent()
    {
        return array(
            // Chrome
            array(
                'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.60 Safari/537.17',
                'chrome',
                '24.0.1312.60',
            ),
            array(
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1309.0 Safari/537.17',
                'chrome',
                '24.0.1309.0'
            ),
            array(
                'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4',
                'chrome',
                '22.0.1229.94'
            ),
            // Firefox
            array(
                'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0',
                'mozilla',
                '20.0'
            ),
            array(
                'Mozilla/6.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1',
                'mozilla',
                '16.0.1'
            ),
            array(
                'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1',
                'mozilla',
                '15.0'
            ),
            array(
                'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:14.0) Gecko/20120405 Firefox/14.0a1',
                'mozilla',
                '14.0'
            ),
            // IE
            array(
                'Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0',
                'msie',
                '10.6'
            ),
            array(
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)',
                'msie',
                '10.0'
            ),
            array(
                'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))',
                'msie',
                '9.0'
            ),
            array(
                'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.2; SV1; .NET CLR 3.3.69573; WOW64; en-US)',
                'msie',
                '8.0'
            ),
            array(
                'Mozilla/5.0 (MSIE 7.0; Macintosh; U; SunOS; X11; gu; SV1; InfoPath.2; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)',
                'msie',
                '7.0'
            ),
            array(
                'Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)',
                'msie',
                '6.0'
            ),
            // Safari
            array(
                'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25',
                'webkit',
                '536.26'
            ),
            array(
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
                'webkit',
                '537.13'
            ),
            array(
                'Mozilla/5.0 (Windows; U; Windows NT 6.1; tr-TR) AppleWebKit/533.20.25 (KHTML, like Gecko)',
                'webkit',
                '533.20.25'
            ),
            // empty
            array(
                '',
                '',
                0
            )
        );
    }
}