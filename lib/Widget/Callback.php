<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The widget for WeChat(Weixin) callback message
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @link        http://mp.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E6%8E%A5%E5%8F%A3%E6%8C%87%E5%8D%97
 */
class Callback extends AbstractWidget
{
    /**
     * The callback token for to generate signature
     * 
     * @var string
     */
    protected $token = 'widget';
    
    /**
     * The user input string
     * 
     * @var string
     */
    protected $input;
    
    /**
     * 开发者微信号
     * 
     * @var string
     */
    protected $to;
    
    /**
     * 发送方帐号（一个OpenID）
     * 
     * @var string
     */
    protected $from;
    
    /**
     * The  HTTP raw post data, equals to $GLOBALS["HTTP_RAW_POST_DATA"] on default
     * 
     * @var string
     */
    protected $postData;
    
    /**
     * 自定回复的消息规则
     * 
     * @var array
     */
    protected $rules = array();
    
    /**
     *
     * @var string
     * @todo 更改更合适的名称
     */
    protected $fallback;
    
    protected $handled = false;
    
    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        
        if (is_null($this->postData) && isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
            $this->postData = $GLOBALS["HTTP_RAW_POST_DATA"];
        }
    }
    
    /**
     * Returns user input string
     * 
     * @return string
     */
    public function getIntput()
    {
        return $this->input;
    }
    
    /**
     * Reurns a user OpenID who send message to you
     * 
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }
    
    /**
     * Returns your id
     * 
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }
    
    /**
     * 进行规则匹配并输入结果
     * 
     * @return \SeekApi\Widget\Callback
     */
    public function __invoke()
    {
        // 校验请求是否来自微信服务器
        $echostr = $this->query('echostr');
        if ($this->checkSignature()) {
            echo $this->escape($echostr);
        } else {
            return $this->response('404');
        }
        
        // 解析用户输入
        $this->parsePostData();
        
        foreach ($this->rules as $rule) {
            switch ($rule['type']) {
                case 'has' :
                    if (false !== strpos($this->input, $rule['expected'])) {
                        $this->handle($rule['fn']);
                    }
                    break;
                
                case 'is' :
                    if ($this->input == $rule['expected']) {
                        $this->handle($rule['fn']);
                    }
                    break;
                
                case 'match' :
                    if (preg_match($rule['expected'], $this->input)) {
                        $this->handle($rule['fn']);
                    }
                    break;
                    
                default :
                    break;
                    
            }
        }
        
        if (!$this->handled) {
            $this->handle($this->fallback);
        }
        
        return $this;
    }
    
    /**
     * 当用户输入的值等于期待值时,执行回调函数 
     * 
     * @param string $expected 期待用户输入的值
     * @param \Closure $fn 回调函数
     * @return boolean
     */
    public function is($expected, \Closure $fn)
    {
        return $this->addRule('is', $expected, $fn);
    }
    
    /**
     * 当用户输入的值包含期待值时,执行回调函数
     * 
     * @param string $expected 期待用户输入的值
     * @param \Closure $fn 回调函数
     * @return boolean
     */
    public function has($expected, \Closure $fn)
    {
        return $this->addRule('has', $expected, $fn);
    }
    
    public function hello(\Closure $fn)
    {
        return $this->addRule('is', 'Hello2BizUser', $fn);
    }
    
    /**
     * 当用户输入的值匹配指定的正则表达式时,执行回调函数
     * 
     * @param string $pattern 正则表达式
     * @param \Closure $fn 回调函数
     * @return boolean
     */
    public function match($pattern, \Closure $fn)
    {
        return $this->addRule('match', $pattern, $fn);
    }
    
    /**
     * 当用户的输入不匹配任何规则时,指定会滴函数
     * 
     * @param \Closure $fn 回调函数
     * @return boolean
     */
    public function fallback(\Closure $fn)
    {
        $this->fallback = $fn;
        return $this;
    }
    
    public function addRule($type, $expected, \Closure $fn)
    {
        $this->rules[] = array(
            'type' => $type,
            'expected' => $expected,
            'fn' => $fn
        );
        return $this;
    }
    
    public function handle($fn)
    {
        $this->handled = true;
        $content = $fn($this, $this->widget);

        $this->responseMsg($content);
    }
    
    public function responseMsg($content)
    {
        if ('<xml>' == substr($content, 0, '5')) {
            echo $content;
        } else {
            echo $this->sendText($content);
        }
    }

    /**
     * Response text message to user
     * 
     * @param string $content
     * @param bool $mark Whenter mark the message or not
     * @return string
     */
    public function sendText($content, $mark = false)
    {
        $body = sprintf('<Content><![CDATA[%s]]></Content>', $content);
        
        return $this->send('text', $body, $mark);
    }
    
    /**
     * Response music message to user
     * 
     * @param string $title The title of music
     * @param string $description The description display blow the title
     * @param string $url The music URL for player
     * @param string $hqUrl The HQ music URL for player when user in WIFI
     * @param string $mark Whenter mark the message or not
     * @return string
     */
    public function sendMusic($title, $description, $url, $hqUrl = null, $mark = false)
    {
        $body = sprintf(
            '<Music>
             <Title><![CDATA[%s]]></Title>
             <Description><![CDATA[%s]]></Description>
             <MusicUrl><![CDATA[%s]]></MusicUrl>
             <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
             </Music>', 
            $title, 
            $description, 
            $url, 
            $hqUrl
        );
        
        return $this->send('music', $body, $mark);    
    }
    
    /**
     * Response article message for user
     * 
     * @param array $articles The article array
     * @param bool $mark Whenter mark the message or not
     * @return string 
     */
    public function sendArticle(array $articles, $mark = false)
    {
        // 单个图文转多条
        if (!is_int(key($articles))) {
            $articles = array($articles);
        }

        $body = '<ArticleCount>' . count($articles) . '</ArticleCount>
                <Articles>';
        
        foreach ($articles as $article) {
            $article += array(
                'title' => null,
                'description' => null,
                'picUrl' => null,
                'url' => null
            );
            $body .= sprintf(
                '<item>
                <Title><![CDATA[%s]]></Title> 
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>', $article['title'], $article['description'], $article['picUrl'], $article['url']);
        }
        
        $body .= '</Articles>';
        
        return $this->send('news', $body, $mark);
    }
    
    /**
     * Generate message for user
     * 
     * @param string $type The type of message
     * @param string $body The body of message
     * @param bool $mark $mark Whenter mark the message or not
     * @return string
     */
    protected function send($type, $body, $mark = false)
    {
        $template = '<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>'
            . $body 
            . '<FuncFlag>%s</FuncFlag>
            </xml>';

        $response = sprintf($template, $this->from, $this->to, time(), $type, (int)$mark); 
        return $response;
    }
    
    /**
     * Parse post data to recive user OpenID and user input
     * 
     * @todo detect invald input
     */
    protected function parsePostData()
    {
        if ($this->postData) {
            $postObj = simplexml_load_string($this->postData, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->from = $postObj->FromUserName;
            $this->to = $postObj->ToUserName;
            $this->input = trim($postObj->Content);
        }
    }
    
    /**
     * Check if the signature is valid
     * 
     * @return bool
     */
    protected function checkSignature()
    {
        $tmpArr = array(
            $this->token, 
            $this->query('timestamp'),
            $this->query('nonce')
        );
        sort($tmpArr);
        $tmpStr = sha1(implode($tmpArr));
        return $tmpStr === $this->query('signature');
    }
}