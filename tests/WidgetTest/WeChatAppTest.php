<?php

namespace WidgetTest;

use Widget\WeChatApp;

class WeChatAppTest extends TestCase
{
    /**
     * @var \Widget\WeChatApp
     */
    protected $object;

    public function testForbiddenForInvalidSignature()
    {
        $app = $this->object;
        $app->setOption('query', array(
            'signature' => 'invalid',
            'timestamp' => 'invalid',
            'nonce'     => 'invalid',
        ));

        $this->expectOutputString('Forbidden');

        $app->parse();
        $return = $app();

        $this->assertInstanceOf('\Widget\WeChatApp', $return);
        $this->assertEquals('403', $this->response->getStatusCode());
    }

    public function testEchostr()
    {
        $app = $this->object;
        $app->setOption('query', array(
            'signature' => 'c61b3d7eab5dfea9b72af0b1574ff2f4d2109583',
            'timestamp' => '1366032735',
            'nonce'     => '1365872231',
            'echostr'   => $rand = mt_rand(0, 100000)
        ));

        //$this->expectOutputString($rand);
        ob_start();
        $app->parse();
        $return = $app();
        $this->assertEquals($rand, ob_get_clean());

        $this->assertInstanceOf('\Widget\WeChatApp', $return);
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testEchorStrOnlyWhenAuth()
    {
        $app = $this->object;

        $app->fallback(function(){
            return 'nerver see me';
        });

        $app->setOption('query', array(
            'signature' => 'c61b3d7eab5dfea9b72af0b1574ff2f4d2109583',
            'timestamp' => '1366032735',
            'nonce'     => '1365872231',
            'echostr'   => $rand = mt_rand(0, 100000)
        ));

        //$this->expectOutputString($rand);
        ob_start();
        $app->parse();
        $app();
        $this->assertEquals($rand, ob_get_clean());
    }

    public function testHttpRawPostData()
    {
        $GLOBALS['HTTP_RAW_POST_DATA'] = 'test';
        $app = new \Widget\WeChatApp();

        $this->assertEquals('test', $app->getOption('postData'));
    }

    /**
     * @dataProvider providerForInputAndOutput
     */
    public function testInputAndOutput($query, $input, $data, $outputContent = null, $mark = null)
    {
        $app = $this->object;

        // Inject HTTP query
        $gets = array();
        parse_str($query, $gets);
        $app->setOption('query', $gets);

        // Inject user input message
        $app->setOption('postData', $input);

        $app->fallback(function($app){
            return "Your input is " . $app->getContent();
        });

        $app->subscribe(function(){
            return 'you are my 100 reader, wonderful!';
        });

        $app->unsubscribe(function(){
            return 'you won\'t see this message';
        });

        $app->click('button', function(){
            return 'you clicked the button';
        });

        $app->click('index', function(){
            return 'you clicked index';
        });

        $app->receiveImage(function($app){
            $app->setMark(true);
            return 'you sent a picture to me';
        });

        $app->receiveLocation(function($app){
            $app->setMark(false);
            return 'the place looks livable';
        });

        $app->receiveVoice(function(){
            return 'u sound like a old man~';
        });

        $app->receiveVideo(function(){
            return 'good video';
        });

        $app->receiveLink(function(){
            return 'got a link';
        });

        $app->is('0', function(){
            return 'your input is 0';
        });

        $app->is('1', function(){
            return 'your input is 1';
        });

        $app->is('2', function(WeChatApp $app){
            return $app->sendMusic('Burning', 'A song of Maria Arredondo', 'url', 'HQ url', true);
        });

        $app->is('3', function(WeChatApp $app){
            return $app->sendArticle(array(
                'title' => 'It\'s fine today',
                'description' => 'A new day is coming~~',
                'picUrl' => 'http://pic-url',
                'url' => 'http://link-url'
            ));
        });

        $app->has('iphone', function(){
            return 'sorry, not this time';
        });

        $app->has('ipad', function(WeChatApp $app){
            return $app->sendText('Find a ipad ? ok, i will remember u', true);
        });

        $that = $this;
        $app->startsWith('t', function($app) use($that){
            $that->assertEquals(' xx', $app->getKeyword());
            return 'The translation result is: xx';
        });

        $app->match('/twin/', function(){
            return 'anyone find my brother?';
        });

        $app->match('/twin/i', function(WeChatApp $app){
            return 'Yes, I\'m here';
        });

        ob_start();
        $app->parse();
        $return = $app();
        $content = ob_get_clean();

        $this->assertInstanceOf('\Widget\WeChatApp', $return);

        foreach ($data as $name => $value) {
            $this->assertEquals($value, $app->{'get' . $name}());
        }

        $output = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->assertEquals($app->getToUserName(), (string)$output->FromUserName);
        $this->assertEquals($app->getFromUserName(), (string)$output->ToUserName);

        // Test mark message
        if (is_bool($mark)) {
            $this->assertEquals($mark, (bool)(string)$output->FuncFlag);
        }

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

                    case 'click' :
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
        return array(
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('0'),
                array(
                    'content' => '0',
                    'msgType' => 'text',
                    'msgId' => '1234567890123456'
                ),
                'your input is 0',
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('1'),
                array(
                    'content' => '1',
                    'msgType' => 'text',
                    'msgId' => '1234567890123456'
                ),
                'your input is 1',
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('2'),
                array(
                    'content' => '2',
                ),
                '', // return music
                true
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('99999'),
                array(

                ),
               'Your input is 99999',
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('t xx'),
                array(

                ),
               'The translation result is: xx',
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('3'),
                array(
                    'content' => '3',
                ),
                '', // return news
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('I want a ipad'),
                array(
                    'content' => 'I want a ipad',
                ),
                'Find a ipad ? ok, i will remember u',
                true
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('Are u Twin?'),
                array(
                    'content' => 'Are u Twin?',
                ),
                'Yes, I\'m here',
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                '<xml>
                 <ToUserName><![CDATA[toUser]]></ToUserName>
                 <FromUserName><![CDATA[fromUser]]></FromUserName>
                 <CreateTime>1366118361</CreateTime>
                 <MsgType><![CDATA[image]]></MsgType>
                 <PicUrl><![CDATA[http://mmsns.qpic.cn/mmsns/X1X15BcJOnSyeD9OtgfgM5RovwBP83QMHpd2YtO8DqtWG5jarm937g/0]]></PicUrl>
                 <MsgId>1234567890123456</MsgId>
                 </xml>',
                array(
                    'createTime' => '1366118361',
                    'msgType' => 'image',
                    'msgId' => '1234567890123456',
                    'picUrl' => 'http://mmsns.qpic.cn/mmsns/X1X15BcJOnSyeD9OtgfgM5RovwBP83QMHpd2YtO8DqtWG5jarm937g/0'
                ),
                true
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
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
                array(
                    'msgType' => 'location',
                    'locationX' => '22.000000',
                    'locationY' => '114.000000',
                    'scale' => '15',
                    'label' => '中国广东省深圳市 邮政编码: 518049',
                    'msgId' => '1234567890123456'
                ),
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366118483</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    <MediaId><![CDATA[vLzm6LJh88oq6xFk5HzC28AbbjQJgnJZH5r5eqBLs_-ddoGK4Hyvai7zvnlL34Si]]></MediaId>
                    <Format><![CDATA[amr]]></Format>
                    <MsgId>1234567890123456</MsgId>
                </xml>',
                array(
                    'msgType' => 'voice',
                    'mediaId' => 'vLzm6LJh88oq6xFk5HzC28AbbjQJgnJZH5r5eqBLs_-ddoGK4Hyvai7zvnlL34Si',
                    'format' => 'amr',
                    'msgId' => '1234567890123456'
                ),
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131823</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[unsubscribe]]></Event>
                    <EventKey><![CDATA[]]></EventKey>
                </xml>',
                array(
                    'msgType' => 'event',
                    'event' => 'unsubscribe',
                    'eventKey' => ''
                ),
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131865</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[subscribe]]></Event>
                    <EventKey><![CDATA[]]></EventKey>
                 </xml>',
                array(
                    'msgType' => 'event',
                    'event' => 'subscribe',
                    'eventKey' => ''
                ),
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131865</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[CLICK]]></Event>
                    <EventKey><![CDATA[index]]></EventKey>
                 </xml>',
                array(
                    'msgType' => 'event',
                    'event' => 'CLICK',
                    'eventKey' => 'index'
                ),
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366131865</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[CLICK]]></Event>
                    <EventKey><![CDATA[button]]></EventKey>
                 </xml>',
                array(
                    'msgType' => 'event',
                    'event' => 'CLICK',
                    'eventKey' => 'button'
                ),
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                '<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1366209162</CreateTime>
                    <MsgType><![CDATA[video]]></MsgType>
                    <MediaId><![CDATA[1ilIgC6h1vmkKqoodLK-PiQy6DhVccDKm0cnLANsbjxKyDldYBTlhSepr3hAg5K9]]></MediaId>
                    <ThumbMediaId><![CDATA[ZWWu54xvKw6PRfEmrdzZuzfPAiKBpQMEPHfB732tF1QHazqp1wvN5nFWF18ppCto]]></ThumbMediaId>
                    <MsgId>1234567890123456</MsgId>
                  </xml>',
                array(
                    'msgType' => 'video',
                    'mediaId' => '1ilIgC6h1vmkKqoodLK-PiQy6DhVccDKm0cnLANsbjxKyDldYBTlhSepr3hAg5K9',
                    'thumbMediaId' => 'ZWWu54xvKw6PRfEmrdzZuzfPAiKBpQMEPHfB732tF1QHazqp1wvN5nFWF18ppCto'
                ),
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
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
                array(
                    'msgType' => 'link',
                    'title' => '公众平台官网链接',
                    'description' => '公众平台官网链接',
                    'url' => 'url'
                ),
                false
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                'invalid xml',
                array(
                    'msgType' => null
                ),
                false
            ),
        );
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
        $app = $this->object;
        $app->setOption('query', array(
            'signature' => 'c61b3d7eab5dfea9b72af0b1574ff2f4d2109583',
            'timestamp' => '1366032735',
            'nonce'     => '1365872231',
            'echostr'   => $rand = mt_rand(0, 100000)
        ));
        $app->setOption('postData', $this->inputTextMessage('hi'));
        $app->parse();

        // Receive data not in callback Closure
        $this->assertEquals('hi', $app->getContent());
    }
}
