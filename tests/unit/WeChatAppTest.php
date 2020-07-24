<?php

namespace WeiTest;

use Wei\WeChatApp;

/**
 * @internal
 */
final class WeChatAppTest extends TestCase
{
    /**
     * @var \Wei\WeChatApp
     */
    protected $object;

    public function testForbiddenForInvalidSignature()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => 'invalid',
                'timestamp' => 'invalid',
                'nonce' => 'invalid',
            ],
        ]);

        $this->assertFalse($app->isValid());
        $this->assertFalse($app->run());

        $this->expectOutputString('');
        $return = $app();
        $this->assertInstanceOf('\Wei\WeChatApp', $return);
    }

    public function testEchostr()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
                'echostr' => $rand = mt_rand(0, 100000),
            ],
        ]);

        $return = $app->run();
        $this->assertEquals($rand, $return);
    }

    public function testEchorStrOnlyWhenAuth()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
                'echostr' => $rand = mt_rand(0, 100000),
            ],
        ]);

        $app->defaults(function () {
            return 'never see me';
        });

        ob_start();
        $app();
        $this->assertEquals($rand, ob_get_clean());
    }

    /**
     * @dataProvider providerForInputAndOutput
     * @param mixed $query
     * @param mixed $input
     * @param mixed $data
     * @param mixed|null $outputContent
     */
    public function testInputAndOutput($query, $input, $data, $outputContent = null)
    {
        // Inject HTTP query
        $gets = [];
        parse_str($query, $gets);

        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => $gets,
            'postData' => $input,
        ]);

        $app->defaults(function ($app) {
            return 'Your input is ' . $app->getContent();
        });

        $app->subscribe(function () {
            return 'you are my 100 reader, wonderful!';
        });

        $app->unsubscribe(function () {
            return 'you won\'t see this message';
        });

        $app->click('button', function () {
            return 'you clicked the button';
        });

        $app->click('index', function () {
            return 'you clicked index';
        });

        $app->receiveImage(function ($app) {
            return 'you sent a picture to me';
        });

        $app->receiveLocation(function ($app) {
            return 'the place looks livable';
        });

        $app->receiveVoice(function () {
            return 'u sound like a old man~';
        });

        $app->receiveVideo(function () {
            return 'good video';
        });

        $app->receiveLink(function () {
            return 'got a link';
        });

        $app->is('0', function () {
            return 'your input is 0';
        });

        $app->is('1', function () {
            return 'your input is 1';
        });

        $app->is('2', function (WeChatApp $app) {
            return $app->sendMusic('Burning', 'A song of Maria Arredondo', 'url', 'HQ url', true);
        });

        $app->is('3', function (WeChatApp $app) {
            return $app->sendArticle([
                'title' => 'It\'s fine today',
                'description' => 'A new day is coming~~',
                'picUrl' => 'http://pic-url',
                'url' => 'http://link-url',
            ]);
        });

        $app->has('iphone', function () {
            return 'sorry, not this time';
        });

        $app->has('ipad', function (WeChatApp $app) {
            return $app->sendText('Find a iPad ? ok, i will remember u', true);
        });

        $that = $this;
        $app->startsWith('t', function ($app) use ($that) {
            return 'The translation result is: xx';
        });

        $app->match('/twin/', function () {
            return 'anyone find my brother?';
        });

        $app->match('/twin/i', function (WeChatApp $app) {
            return 'Yes, I\'m here';
        });

        ob_start();
        $return = $app();
        $content = ob_get_clean();

        $this->assertTrue($app->isValid());
        $this->assertInstanceOf('\Wei\WeChatApp', $return);

        foreach ($data as $name => $value) {
            $this->assertEquals($value, $app->{'get' . $name}());
        }

        $output = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->assertEquals($app->getToUserName(), (string) $output->FromUserName);
        $this->assertEquals($app->getFromUserName(), (string) $output->ToUserName);

        // Test message content
        switch ($app->getMsgType()) {
            case 'text':
                $this->assertEquals($outputContent, $output->Content);
                break;

            case 'image':
                $this->assertEquals('you sent a picture to me', $output->Content);
                break;

            case 'location':
                $this->assertEquals('the place looks livable', $output->Content);
                break;

            case 'voice':
                $this->assertEquals('u sound like a old man~', $output->Content);
                break;

            case 'video':
                $this->assertEquals('good video', $output->Content);
                break;

            case 'link':
                $this->assertEquals('got a link', $output->Content);
                break;

            case 'event':
                switch ($app->getEvent()) {
                    case 'subscribe':
                        $this->assertEquals('you are my 100 reader, wonderful!', $output->Content);
                        break;

                    case 'unsubscribe':
                        $this->assertEquals('you won\'t see this message', $output->Content);
                        break;

                    case 'click':
                        switch ($app->getEventKey()) {
                            case 'button':
                                $this->assertEquals('you clicked the button', $output->Content);
                                break;

                            case 'index':
                                $this->assertEquals('you clicked index', $output->Content);
                                break;
                        }
                        break;
                }
        }

        switch ($output->MsgType) {
            case 'music':
                $this->assertEquals('Burning', $output->Music->Title);
                $this->assertEquals('A song of Maria Arredondo', $output->Music->Description);
                $this->assertEquals('url', $output->Music->MusicUrl);
                $this->assertEquals('HQ url', $output->Music->HQMusicUrl);
                break;

            case 'news':
                $this->assertEquals('1', $output->ArticleCount);
                $this->assertEquals('It\'s fine today', $output->Articles->item->Title);
                $this->assertEquals('A new day is coming~~', $output->Articles->item->Description);
                $this->assertEquals('http://pic-url', $output->Articles->item->PicUrl);
                $this->assertEquals('http://link-url', $output->Articles->item->Url);
                break;
        }
    }

    public function providerForInputAndOutput()
    {
        return [
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('0'),
                [
                    'content' => '0',
                    'msgType' => 'text',
                    'msgId' => '1234567890123456',
                ],
                'your input is 0',
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('1'),
                [
                    'content' => '1',
                    'msgType' => 'text',
                    'msgId' => '1234567890123456',
                ],
                'your input is 1',
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('2'),
                [
                    'content' => '2',
                ],
                '', // return music
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('99999'),
                [
                ],
                'Your input is 99999',
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('t xx'),
                [
                ],
                'The translation result is: xx',
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('3'),
                [
                    'content' => '3',
                ],
                '', // return news
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('I want a iPad'),
                [
                    'content' => 'I want a iPad',
                ],
                'Find a iPad ? ok, i will remember u',
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('Are u Twin?'),
                [
                    'content' => 'Are u Twin?',
                ],
                'Yes, I\'m here',
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                '<xml>
                 <ToUserName><![CDATA[toUser]]></ToUserName>
                 <FromUserName><![CDATA[fromUser]]></FromUserName>
                 <CreateTime>1366118361</CreateTime>
                 <MsgType><![CDATA[image]]></MsgType>
                 <PicUrl><![CDATA[http://mmsns.qpic.cn/mmsns/X1X15BcJOnSyeD9OtgfgM5RovwBP83QMHpd2YtO8DqtWG5jarm937g/0]]></PicUrl>
                 <MsgId>1234567890123456</MsgId>
                 </xml>',
                [
                    'createTime' => '1366118361',
                    'msgType' => 'image',
                    'msgId' => '1234567890123456',
                    'picUrl' => 'http://mmsns.qpic.cn/mmsns/X1X15BcJOnSyeD9OtgfgM5RovwBP83QMHpd2YtO8DqtWG5jarm937g/0',
                ],
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366118469</CreateTime>
                    <MsgType><![CDATA[location]]></MsgType>
                    <Location_X>22.000000</Location_X>
                    <Location_Y>114.000000</Location_Y>
                    <Scale>15</Scale>
                    <Label><![CDATA[中国广东省深圳市 邮政编码: 518049]]></Label>
                    <MsgId>1234567890123456</MsgId>
                 </xml>',
                [
                    'msgType' => 'location',
                    'locationX' => '22.000000',
                    'locationY' => '114.000000',
                    'scale' => '15',
                    'label' => '中国广东省深圳市 邮政编码: 518049',
                    'msgId' => '1234567890123456',
                ],
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366118483</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    <MediaId><![CDATA[vLzm6LJh88oq6xFk5HzC28AbbjQJgnJZH5r5eqBLs_-ddoGK4Hyvai7zvnlL34Si]]></MediaId>
                    <Format><![CDATA[amr]]></Format>
                    <MsgId>1234567890123456</MsgId>
                </xml>',
                [
                    'msgType' => 'voice',
                    'mediaId' => 'vLzm6LJh88oq6xFk5HzC28AbbjQJgnJZH5r5eqBLs_-ddoGK4Hyvai7zvnlL34Si',
                    'format' => 'amr',
                    'msgId' => '1234567890123456',
                ],
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131823</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[unsubscribe]]></Event>
                    <EventKey><![CDATA[]]></EventKey>
                </xml>',
                [
                    'msgType' => 'event',
                    'event' => 'unsubscribe',
                    'eventKey' => '',
                ],
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131865</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[subscribe]]></Event>
                    <EventKey><![CDATA[]]></EventKey>
                 </xml>',
                [
                    'msgType' => 'event',
                    'event' => 'subscribe',
                    'eventKey' => '',
                ],
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131865</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[CLICK]]></Event>
                    <EventKey><![CDATA[index]]></EventKey>
                 </xml>',
                [
                    'msgType' => 'event',
                    'event' => 'CLICK',
                    'eventKey' => 'index',
                ],
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131865</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[CLICK]]></Event>
                    <EventKey><![CDATA[button]]></EventKey>
                 </xml>',
                [
                    'msgType' => 'event',
                    'event' => 'CLICK',
                    'eventKey' => 'button',
                ],
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366209162</CreateTime>
                    <MsgType><![CDATA[video]]></MsgType>
                    <MediaId><![CDATA[1ilIgC6h1vmkKqoodLK-PiQy6DhVccDKm0cnLANsbjxKyDldYBTlhSepr3hAg5K9]]></MediaId>
                    <ThumbMediaId><![CDATA[ZWWu54xvKw6PRfEmrdzZuzfPAiKBpQMEPHfB732tF1QHazqp1wvN5nFWF18ppCto]]></ThumbMediaId>
                    <MsgId>1234567890123456</MsgId>
                  </xml>',
                [
                    'msgType' => 'video',
                    'mediaId' => '1ilIgC6h1vmkKqoodLK-PiQy6DhVccDKm0cnLANsbjxKyDldYBTlhSepr3hAg5K9',
                    'thumbMediaId' => 'ZWWu54xvKw6PRfEmrdzZuzfPAiKBpQMEPHfB732tF1QHazqp1wvN5nFWF18ppCto',
                ],
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1351776360</CreateTime>
                    <MsgType><![CDATA[link]]></MsgType>
                    <Title><![CDATA[公众平台官网链接]]></Title>
                    <Description><![CDATA[公众平台官网链接]]></Description>
                    <Url><![CDATA[url]]></Url>
                    <MsgId>1234567890123456</MsgId>
                 </xml>',
                [
                    'msgType' => 'link',
                    'title' => '公众平台官网链接',
                    'description' => '公众平台官网链接',
                    'url' => 'url',
                ],
            ],
            [
                'signature=46816a3b00bfd8ed18826278f140395fcdd5af8f&timestamp=1366032735&nonce=1365872231',
                'invalid xml',
                [
                    'msgType' => null,
                ],
            ],
            // Test for WeChat sort bug
            // https://mp.weixin.qq.com/cgi-bin/readtemplate?t=news/php-sdk_tmpl&lang=zh_CN
            [
                'signature=f73bdd6d07293461e27b2c3921e71df54ee1c3fc&timestamp=1393983248&nonce=1868297319',
                '',
                [
                ],
            ],
            [
                'signature=98cf92919c7a015019893e7967ef94972e3d0e38&timestamp=1393983225&nonce=986423648',
                '',
                [
                ],
            ],
        ];
    }

    public function inputTextMessage($input)
    {
        return '<xml>
                <ToUserName><![CDATA[toUser]]></ToUserName>
                <FromUserName><![CDATA[fromUser]]></FromUserName>
                <CreateTime>1348831860</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[' . $input . ']]></Content>
                <MsgId>1234567890123456</MsgId>
                </xml>';
    }

    public function testFlatMode()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
                'echostr' => $rand = mt_rand(0, 100000),
            ],
            'postData' => $this->inputTextMessage('hi'),
        ]);

        // Receive data not in callback Closure
        $this->assertEquals('hi', $app->getContent());
    }

    public function testIsVerifyToken()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
                'echostr' => $rand = mt_rand(0, 100000),
            ],
            'postData' => $this->inputTextMessage('hi'),
        ]);
        $this->assertTrue($app->isVerifyToken());
    }

    public function testIsNotVerifyToken()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => $this->inputTextMessage('hi'),
        ]);
        $this->assertFalse($app->isVerifyToken());
    }

    /**
     * @dataProvider providerForCase
     * @param mixed $input
     * @param mixed $output
     */
    public function testCase($input, $output)
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => $this->inputTextMessage($input),
        ]);

        $app->is('abc', function () {
            return 'abc';
        });

        $app->startsWith('d', function () {
            return 'd';
        });

        $app->has('e', function () {
            return 'e';
        });

        // Execute and parse result
        $result = $app->run();

        $result = simplexml_load_string($result);

        $this->assertEquals($output, $result->Content);
    }

    public function providerForCase()
    {
        return [
            ['abc', 'abc'],
            ['ABC', 'abc'], // Case insensitive
            ['Abc', 'abc'],
            ['dabc', 'd'],
            ['Dabc', 'd'],
            ['d中文', 'd'],
            ['e', 'e'],
            ['EAbc', 'e'],
            ['ARE', 'e'],
        ];
    }

    public function testNoRuleHandled()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => $this->inputTextMessage('test'),
        ]);

        // Execute and parse result
        $result = $app->run();

        $this->assertEquals('success', $result);
    }

    public function testNotHandleInDefault()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => $this->inputTextMessage('test'),
        ]);

        $app->defaults(function () {
        });

        // Execute and parse result
        $result = $app->run();

        $this->assertEquals('success', $result);
    }

    public function testHasEventButNotMatch()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131865</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[CLICK]]></Event>
                    <EventKey><![CDATA[index]]></EventKey>
                 </xml>',
        ]);

        $app->click('my', function () {
            return 'My info';
        });

        $this->assertEquals('success', $app->run());
    }

    public function providerForScan()
    {
        return [
            [
                // Scan after subscribe
                'postData' => '<xml><ToUserName><![CDATA[ToUserName]]></ToUserName>
<FromUserName><![CDATA[FromUserName]]></FromUserName>
<CreateTime>1394729701</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[SCAN]]></Event>
<EventKey><![CDATA[1]]></EventKey>
<Ticket><![CDATA[gQGS8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2FFMmtOc0hseEhpOU05YUdzR093AAIE0OAhUwMECAcAAA==]]></Ticket>
</xml>',
                'sceneId' => 1,
                'result' => 'scan',
                'calledSubscribe' => false,
            ],
            [
                // Scan and subscribe
                'postData' => '<xml><ToUserName><![CDATA[ToUserName]]></ToUserName>
<FromUserName><![CDATA[FromUserName]]></FromUserName>
<CreateTime>1394729846</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
<EventKey><![CDATA[qrscene_2]]></EventKey>
<Ticket><![CDATA[gQGS8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2FFMmtOc0hseEhpOU05YUdzR093AAIE0OAhUwMECAcAAA==]]></Ticket>
</xml>',
                'sceneId' => 2,
                'result' => 'subscribe',
                'calledSubscribe' => true,
            ],
            [
                // subscribe
                'postData' => '<xml><ToUserName><![CDATA[ToUserName]]></ToUserName>
<FromUserName><![CDATA[FromUserName]]></FromUserName>
<CreateTime>1394730389</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
<EventKey><![CDATA[]]></EventKey>
</xml>',
                'sceneId' => false,
                'result' => 'subscribe',
                'calledSubscribe' => true,
            ],
        ];
    }

    /**
     * @dataProvider providerForScan
     * @param mixed $postData
     * @param mixed $sceneId
     * @param mixed $result
     * @param mixed $calledSubscribe
     */
    public function testScan($postData, $sceneId, $result, $calledSubscribe)
    {
        $test = $this;
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => $postData,
        ]);

        $subscribeFlag = false;

        $this->assertEquals($sceneId, $app->getScanSceneId());

        $app->subscribe(function () use (&$subscribeFlag) {
            $subscribeFlag = true;
            return 'subscribe';
        });

        $app->scan(function (WeChatApp $app) use ($test, $sceneId) {
            $test->assertEquals($sceneId, $app->getScanSceneId());
            return 'scan';
        });

        $app->defaults(function () {
            return 'This is the default message';
        });

        $resultXml = $app->run();

        $this->assertStringNotContainsString('This is the default message', $resultXml);
        $this->assertStringContainsString($result, $resultXml);

        $this->assertSame($calledSubscribe, $subscribeFlag);
    }

    public function testScanAndSubscribe()
    {
        $test = $this;
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => '<xml><ToUserName><![CDATA[ToUserName]]></ToUserName>
<FromUserName><![CDATA[FromUserName]]></FromUserName>
<CreateTime>1394729846</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
<EventKey><![CDATA[qrscene_2]]></EventKey>
<Ticket><![CDATA[gQGS8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2FFMmtOc0hseEhpOU05YUdzR093AAIE0OAhUwMECAcAAA==]]></Ticket>
</xml>',
        ]);

        $app->scan(function (WeChatApp $app) use ($test) {
            $test->assertEquals('2', $app->getScanSceneId());
            return 'scan';
        });

        $resultXml = $app->run();

        $this->assertStringContainsString('scan', $resultXml);
    }

    public function testEncrypt()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'appId' => 'wxbad0b45542aa0b5e',
            'token' => 'weixin',
            'encodingAesKey' => 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG',
            'query' => [
                'encrypt_type' => 'aes',
                'msg_signature' => '6147984331daf7a1a9eed6e0ec3ba69055256154',
                'signature' => '35703636de2f9df2a77a662b68e521ce17c34db4',
                'timestamp' => '1414243737',
                'nonce' => '1792106704',
            ],
            'postData' => '<xml>
    <ToUserName><![CDATA[gh_680bdefc8c5d]]></ToUserName>
    <Encrypt><![CDATA[MNn4+jJ/VsFh2gUyKAaOJArwEVYCvVmyN0iXzNarP3O6vXzK62ft1/KG2/XPZ4y5bPWU/jfIfQxODRQ7sLkUsrDRqsWimuhIT8Eq+w4E/28m+XDAQKEOjWTQIOp1p6kNsIV1DdC3B+AtcKcKSNAeJDr7x7GHLx5DZYK09qQsYDOjP6R5NqebFjKt/NpEl/GU3gWFwG8LCtRNuIYdK5axbFSfmXbh5CZ6Bk5wSwj5fu5aS90cMAgUhGsxrxZTY562QR6c+3ydXxb+GHI5w+qA+eqJjrQqR7u5hS+1x5sEsA7vS+bZ5LYAR3+PZ243avQkGllQ+rg7a6TeSGDxxhvLw+mxxinyk88BNHkJnyK//hM1k9PuvuLAASdaud4vzRQlAmnYOslZl8CN7gjCjV41skUTZv3wwGPxvEqtm/nf5fQ=]]></Encrypt>
</xml>',
        ]);

        $app->is('?', function () {
            return 'xx';
        });

        $xml = $app->run();
        $attrs = $this->xmlToArray($xml);

        $this->assertArrayHasKey('Encrypt', $attrs);
        $this->assertArrayHasKey('MsgSignature', $attrs);
        $this->assertArrayHasKey('TimeStamp', $attrs);
        $this->assertArrayHasKey('Nonce', $attrs);
    }

    public function testEncryptNoRuleReturnSuccess()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'appId' => 'wxbad0b45542aa0b5e',
            'token' => 'weixin',
            'encodingAesKey' => 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG',
            'query' => [
                'encrypt_type' => 'aes',
                'msg_signature' => '6147984331daf7a1a9eed6e0ec3ba69055256154',
                'signature' => '35703636de2f9df2a77a662b68e521ce17c34db4',
                'timestamp' => '1414243737',
                'nonce' => '1792106704',
            ],
            'postData' => '<xml>
    <ToUserName><![CDATA[gh_680bdefc8c5d]]></ToUserName>
    <Encrypt><![CDATA[MNn4+jJ/VsFh2gUyKAaOJArwEVYCvVmyN0iXzNarP3O6vXzK62ft1/KG2/XPZ4y5bPWU/jfIfQxODRQ7sLkUsrDRqsWimuhIT8Eq+w4E/28m+XDAQKEOjWTQIOp1p6kNsIV1DdC3B+AtcKcKSNAeJDr7x7GHLx5DZYK09qQsYDOjP6R5NqebFjKt/NpEl/GU3gWFwG8LCtRNuIYdK5axbFSfmXbh5CZ6Bk5wSwj5fu5aS90cMAgUhGsxrxZTY562QR6c+3ydXxb+GHI5w+qA+eqJjrQqR7u5hS+1x5sEsA7vS+bZ5LYAR3+PZ243avQkGllQ+rg7a6TeSGDxxhvLw+mxxinyk88BNHkJnyK//hM1k9PuvuLAASdaud4vzRQlAmnYOslZl8CN7gjCjV41skUTZv3wwGPxvEqtm/nf5fQ=]]></Encrypt>
</xml>',
        ]);

        $xml = $app->run();

        $this->assertEquals('success', $xml);
    }

    public function testEncryptEmptyReturnSuccess()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'appId' => 'wxbad0b45542aa0b5e',
            'token' => 'weixin',
            'encodingAesKey' => 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG',
            'query' => [
                'encrypt_type' => 'aes',
                'msg_signature' => '6147984331daf7a1a9eed6e0ec3ba69055256154',
                'signature' => '35703636de2f9df2a77a662b68e521ce17c34db4',
                'timestamp' => '1414243737',
                'nonce' => '1792106704',
            ],
            'postData' => '<xml>
    <ToUserName><![CDATA[gh_680bdefc8c5d]]></ToUserName>
    <Encrypt><![CDATA[MNn4+jJ/VsFh2gUyKAaOJArwEVYCvVmyN0iXzNarP3O6vXzK62ft1/KG2/XPZ4y5bPWU/jfIfQxODRQ7sLkUsrDRqsWimuhIT8Eq+w4E/28m+XDAQKEOjWTQIOp1p6kNsIV1DdC3B+AtcKcKSNAeJDr7x7GHLx5DZYK09qQsYDOjP6R5NqebFjKt/NpEl/GU3gWFwG8LCtRNuIYdK5axbFSfmXbh5CZ6Bk5wSwj5fu5aS90cMAgUhGsxrxZTY562QR6c+3ydXxb+GHI5w+qA+eqJjrQqR7u5hS+1x5sEsA7vS+bZ5LYAR3+PZ243avQkGllQ+rg7a6TeSGDxxhvLw+mxxinyk88BNHkJnyK//hM1k9PuvuLAASdaud4vzRQlAmnYOslZl8CN7gjCjV41skUTZv3wwGPxvEqtm/nf5fQ=]]></Encrypt>
</xml>',
        ]);

        $app->is('?', function () {
        });

        $xml = $app->run();

        $this->assertEquals('success', $xml);
    }

    public function testEncryptMsgSignatureErr()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'appId' => 'wxbad0b45542aa0b5e',
            'token' => 'weixin',
            'encodingAesKey' => 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG',
            'query' => [
                'encrypt_type' => 'aes',
                'msg_signature' => '123',
                'signature' => '35703636de2f9df2a77a662b68e521ce17c34db4',
                'timestamp' => '1414243737',
                'nonce' => '1792106704',
            ],
            'postData' => '<xml>
    <ToUserName><![CDATA[gh_680bdefc8c5d]]></ToUserName>
    <Encrypt><![CDATA[MNn4+jJ/VsFh2gUyKAaOJArwEVYCvVmyN0iXzNarP3O6vXzK62ft1/KG2/XPZ4y5bPWU/jfIfQxODRQ7sLkUsrDRqsWimuhIT8Eq+w4E/28m+XDAQKEOjWTQIOp1p6kNsIV1DdC3B+AtcKcKSNAeJDr7x7GHLx5DZYK09qQsYDOjP6R5NqebFjKt/NpEl/GU3gWFwG8LCtRNuIYdK5axbFSfmXbh5CZ6Bk5wSwj5fu5aS90cMAgUhGsxrxZTY562QR6c+3ydXxb+GHI5w+qA+eqJjrQqR7u5hS+1x5sEsA7vS+bZ5LYAR3+PZ243avQkGllQ+rg7a6TeSGDxxhvLw+mxxinyk88BNHkJnyK//hM1k9PuvuLAASdaud4vzRQlAmnYOslZl8CN7gjCjV41skUTZv3wwGPxvEqtm/nf5fQ=]]></Encrypt>
</xml>',
        ]);

        $ret = $app->parse();

        $this->assertEquals(-2001, $ret['code']);
        $this->assertEquals('签名验证错误', $ret['message']);
    }

    public function testEncryptFromAppIdErr()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'appId' => 'wxbad0b45542aa0b5e1',
            'token' => 'weixin',
            'encodingAesKey' => 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG',
            'query' => [
                'encrypt_type' => 'aes',
                'msg_signature' => '6147984331daf7a1a9eed6e0ec3ba69055256154',
                'signature' => '35703636de2f9df2a77a662b68e521ce17c34db4',
                'timestamp' => '1414243737',
                'nonce' => '1792106704',
            ],
            'postData' => '<xml>
    <ToUserName><![CDATA[gh_680bdefc8c5d]]></ToUserName>
    <Encrypt><![CDATA[MNn4+jJ/VsFh2gUyKAaOJArwEVYCvVmyN0iXzNarP3O6vXzK62ft1/KG2/XPZ4y5bPWU/jfIfQxODRQ7sLkUsrDRqsWimuhIT8Eq+w4E/28m+XDAQKEOjWTQIOp1p6kNsIV1DdC3B+AtcKcKSNAeJDr7x7GHLx5DZYK09qQsYDOjP6R5NqebFjKt/NpEl/GU3gWFwG8LCtRNuIYdK5axbFSfmXbh5CZ6Bk5wSwj5fu5aS90cMAgUhGsxrxZTY562QR6c+3ydXxb+GHI5w+qA+eqJjrQqR7u5hS+1x5sEsA7vS+bZ5LYAR3+PZ243avQkGllQ+rg7a6TeSGDxxhvLw+mxxinyk88BNHkJnyK//hM1k9PuvuLAASdaud4vzRQlAmnYOslZl8CN7gjCjV41skUTZv3wwGPxvEqtm/nf5fQ=]]></Encrypt>
</xml>',
        ]);

        $ret = $app->parse();

        $this->assertEquals(-2005, $ret['code']);
        $this->assertEquals('AppId 校验错误', $ret['message']);
    }

    public function testEncryptAesErr()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'appId' => 'wxbad0b45542aa0b5e',
            'token' => 'weixin',
            'encodingAesKey' => '12',
            'query' => [
                'encrypt_type' => 'aes',
                'msg_signature' => '6147984331daf7a1a9eed6e0ec3ba69055256154',
                'signature' => '35703636de2f9df2a77a662b68e521ce17c34db4',
                'timestamp' => '1414243737',
                'nonce' => '1792106704',
            ],
            'postData' => '<xml>
    <ToUserName><![CDATA[gh_680bdefc8c5d]]></ToUserName>
    <Encrypt><![CDATA[MNn4+jJ/VsFh2gUyKAaOJArwEVYCvVmyN0iXzNarP3O6vXzK62ft1/KG2/XPZ4y5bPWU/jfIfQxODRQ7sLkUsrDRqsWimuhIT8Eq+w4E/28m+XDAQKEOjWTQIOp1p6kNsIV1DdC3B+AtcKcKSNAeJDr7x7GHLx5DZYK09qQsYDOjP6R5NqebFjKt/NpEl/GU3gWFwG8LCtRNuIYdK5axbFSfmXbh5CZ6Bk5wSwj5fu5aS90cMAgUhGsxrxZTY562QR6c+3ydXxb+GHI5w+qA+eqJjrQqR7u5hS+1x5sEsA7vS+bZ5LYAR3+PZ243avQkGllQ+rg7a6TeSGDxxhvLw+mxxinyk88BNHkJnyK//hM1k9PuvuLAASdaud4vzRQlAmnYOslZl8CN7gjCjV41skUTZv3wwGPxvEqtm/nf5fQ=]]></Encrypt>
</xml>',
        ]);

        $ret = $app->parse();
        $this->assertEquals(-2002, $ret['code']);
        $this->assertEquals('AES解密失败', $ret['message']);
        $this->assertStringContainsString('openssl_decrypt(): IV passed is only 1 bytes long, cipher expects an IV of precisely 16 bytes, padding with \0', $ret['e']);
    }

    public function testGetAttrs()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => $this->inputTextMessage('hi'),
        ]);

        $attrs = $app->getAttrs();

        $this->assertIsArray($attrs);

        $this->assertEquals('toUser', $attrs['ToUserName']);
        $this->assertEquals('fromUser', $attrs['FromUserName']);
        $this->assertEquals('1348831860', $attrs['CreateTime']);
        $this->assertEquals('text', $attrs['MsgType']);
        $this->assertEquals('hi', $attrs['Content']);
        $this->assertEquals('1234567890123456', $attrs['MsgId']);
    }

    public function testGetKeyword()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => $this->inputTextMessage('hi'),
        ]);

        $this->assertEquals('hi', $app->getKeyword());
    }

    public function testGetKeywordFromEvent()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131865</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[CLICK]]></Event>
                    <EventKey><![CDATA[index]]></EventKey>
                 </xml>',
        ]);

        $this->assertEquals('index', $app->getKeyword());
    }

    public function testGetKeywordFromSubscribe()
    {
        $app = new \Wei\WeChatApp([
            'wei' => $this->wei,
            'query' => [
                'signature' => '46816a3b00bfd8ed18826278f140395fcdd5af8f',
                'timestamp' => '1366032735',
                'nonce' => '1365872231',
            ],
            'postData' => '<xml>
<ToUserName><![CDATA[toUser]]></ToUserName>
<FromUserName><![CDATA[fromUser]]></FromUserName>
<CreateTime>1366131865</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
<EventKey><![CDATA[]]></EventKey>
</xml>',
        ]);

        $this->assertFalse($app->getKeyword());
    }

    /**
     * Convert XML string to array
     *
     * @param string $xml
     * @return array
     */
    protected function xmlToArray($xml)
    {
        // Do not output libxml error messages to screen
        $useErrors = libxml_use_internal_errors(true);
        $array = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        libxml_use_internal_errors($useErrors);

        // Fix the issue that XML parse empty data to new SimpleXMLElement object
        return array_map('strval', (array) $array);
    }
}
