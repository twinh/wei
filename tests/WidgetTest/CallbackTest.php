<?php

namespace WidgetTest;

use Widget\Callback;

/**
 * @property \Widget\Callback $callback The WeChat callback widget
 */
class CallbackTest extends TestCase
{
    public function testForbiddenForInvalidSignature()
    {
        $callback = $this->callback;
        $this->query->set('signature', 'invalid');
        $this->query->set('timestamp', 'invalid');
        $this->query->set('nonce', 'invalid');

        $this->expectOutputString('Forbidden');

        $callback->parse();
        $return = $callback();

        $this->assertInstanceOf('\Widget\Callback', $return);
        $this->assertEquals('403', $this->response->getStatusCode());
    }

    public function testEchostr()
    {
        $callback = $this->callback;
        $this->query->set('signature', 'c61b3d7eab5dfea9b72af0b1574ff2f4d2109583');
        $this->query->set('timestamp', '1366032735');
        $this->query->set('nonce', '1365872231');
        $this->query->set('echostr', $rand = mt_rand(0, 100000));

        //$this->expectOutputString($rand);
        ob_start();
        $callback->parse();
        $return = $callback();
        $this->assertEquals($rand, ob_get_clean());

        $this->assertInstanceOf('\Widget\Callback', $return);
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testEchorStrOnlyWhenAuth()
    {
        $cb = $this->callback;

        $cb->fallback(function(){
            return 'nerver see me';
        });

        $this->query->set('signature', 'c61b3d7eab5dfea9b72af0b1574ff2f4d2109583');
        $this->query->set('timestamp', '1366032735');
        $this->query->set('nonce', '1365872231');
        $this->query->set('echostr', $rand = mt_rand(0, 100000));

        //$this->expectOutputString($rand);
        ob_start();
        $cb->parse();
        $cb();
        $this->assertEquals($rand, ob_get_clean());
    }

    public function testHttpRawPostData()
    {
        $GLOBALS['HTTP_RAW_POST_DATA'] = 'test';
        $callback = new \Widget\Callback();

        $this->assertEquals('test', $callback->getOption('postData'));
    }

    /**
     * @dataProvider providerForInputAndOutput
     */
    public function testInputAndOutput($query, $input, $data, $outputContent = null, $mark = null)
    {
        $cb = $this->callback;

        // Inject HTTP query
        $gets = array();
        parse_str($query, $gets);
        $this->request->setOption('gets', $gets);

        // Inject user input message
        $cb->setOption('postData', $input);

        $cb->fallback(function($callback){
            return "Your input is " . $callback->getContent();
        });

        $cb->subscribe(function(){
            return 'you are my 100 reader, wonderful!';
        });

        $cb->unsubscribe(function(){
            return 'you won\'t see this message';
        });

        $cb->click('button', function(){
            return 'you clicked the button';
        });

        $cb->click('index', function(){
            return 'you clicked index';
        });

        $cb->receiveImage(function($cb){
            $cb->setMark(true);
            return 'you sent a picture to me';
        });

        $cb->receiveLocation(function($cb){
            $cb->setMark(false);
            return 'the place looks livable';
        });

        $cb->receiveVoice(function(){
            return 'u sound like a old man~';
        });

        $cb->receiveVideo(function(){
            return 'good video';
        });

        $cb->receiveLink(function(){
            return 'got a link';
        });

        $cb->is('0', function(){
            return 'your input is 0';
        });

        $cb->is('1', function(){
            return 'your input is 1';
        });

        $cb->is('2', function(Callback $cb){
            return $cb->sendMusic('Burning', 'A song of Maria Arredondo', 'url', 'HQ url', true);
        });

        $cb->is('3', function(Callback $cb){
            return $cb->sendArticle(array(
                'title' => 'It\'s fine today',
                'description' => 'A new day is coming~~',
                'picUrl' => 'http://pic-url',
                'url' => 'http://link-url'
            ));
        });

        $cb->has('iphone', function(){
            return 'sorry, not this time';
        });

        $cb->has('ipad', function(Callback $cb){
            return $cb->sendText('Find a ipad ? ok, i will remember u', true);
        });

        $that = $this;
        $cb->startsWith('t', function($cb) use($that){
            $that->assertEquals(' xx', $cb->getKeyword());
            return 'The translation result is: xx';
        });

        $cb->match('/twin/', function(){
            return 'anyone find my brother?';
        });

        $cb->match('/twin/i', function(Callback $cb){
            return 'Yes, I\'m here';
        });

        ob_start();
        $cb->parse();
        $return = $cb();
        $content = ob_get_clean();

        $this->assertInstanceOf('\Widget\Callback', $return);

        foreach ($data as $name => $value) {
            $this->assertEquals($value, $cb->{'get' . $name}());
        }

        $output = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->assertEquals($cb->getToUserName(), (string)$output->FromUserName);
        $this->assertEquals($cb->getFromUserName(), (string)$output->ToUserName);

        // Test mark message
        if (is_bool($mark)) {
            $this->assertEquals($mark, (bool)(string)$output->FuncFlag);
        }

        // Test message content
        switch ($cb->getMsgType()) {
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
                switch ($cb->getEvent()) {
                    case 'subscribe':
                        $this->assertEquals('you are my 100 reader, wonderful!', $output->Content);
                        break;

                    case 'unsubscribe':
                        $this->assertEquals('you won\'t see this message', $output->Content);
                        break;

                    case 'click' :
                        switch ($cb->getEventKey()) {
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
        $cb = $this->callback;
        $this->query->set('signature', 'c61b3d7eab5dfea9b72af0b1574ff2f4d2109583');
        $this->query->set('timestamp', '1366032735');
        $this->query->set('nonce', '1365872231');
        $cb->setOption('postData', $this->inputTextMessage('hi'));
        $cb->parse();

        // Receive data not in callback Closure
        $this->assertEquals('hi', $cb->getContent());
    }
}
