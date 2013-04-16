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
     * Callback接口的验证token
     * 
     * @var string
     */
    protected $token = 'seekabcbbccbc';
    
    /**
     * 用户输入的数据
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
    
    protected $postData = ' <xml>
        <ToUserName><![CDATA[toUser]]></ToUserName>
        <FromUserName><![CDATA[fromUser]]></FromUserName> 
        <CreateTime>1348831860</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[Hello2BizUser]]></Content>
        <MsgId>1234567890123456</MsgId>
        </xml>';
    
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
     * 构造器
     * 
     * @param array $options 配置选项
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
    }
    
    /**
     * 获取用户发送的文本信息
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
    
    protected function parseInput()
    {
        $postData = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : $this->postData;
        $postObj = simplexml_load_string($postData, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->from = $postObj->FromUserName;
        $this->to = $postObj->ToUserName;
        $this->input = trim($postObj->Content);
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
        $this->parseInput();
        
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
     * 检查签名是否通过验证
     * 
     * @return bool
     */
    private function checkSignature()
    {
        $signature = $this->query('signature');
        $timestamp = $this->query('timestamp');
        $nonce = $this->query('nonce');

        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = sha1(implode( $tmpArr ));

        return $tmpStr === $signature;
    }
    
    public function sendText($content, $mark = false)
    {
        $body = sprintf('<Content><![CDATA[%s]]></Content>', $content);
        
        return $this->send('text', $body, $mark);
    }
    
    public function sendMusic($title, $description, $url, $hqUrl = null, $mark = false)
    {
        $body = sprintf('<Music>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <MusicUrl><![CDATA[%s]]></MusicUrl>
                <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
            </Music>', $title, $description, $url, $hqUrl);
        
        return $this->send('music', $body, $mark);    
    }
    
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
            $body .= sprintf('<item>
                <Title><![CDATA[%s]]></Title> 
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>', $article['title'], $article['description'], $article['picUrl'], $article['url']);
        }
        
        $body .= '</Articles>';
        
        return $this->send('news', $body, $mark);
    }
    
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
}