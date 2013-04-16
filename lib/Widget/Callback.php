<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use \SimpleXMLElement;

/**
 * The widget for WeChat(Weixin) callback message
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @link        http://mp.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E6%8E%A5%E5%8F%A3%E6%8C%87%E5%8D%97
 * @method      \Widget\Response response(string $content, int $status) Send headers and output content
 * @method      string query(string $name) Returns the URL query parameter value
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
    protected $content;
    
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
     * The message id
     * 
     * @var string
     */
    protected $msgId;
    
    /**
     * The message type, currently could be text, image, location, link or event
     * 
     * @var string
     */
    protected $msgType;
    
    /**
     * The picture URL, available when the message type is image
     * 
     * @var string
     */
    protected $picUrl;
    
    /**
     * The latitude of location, available when the message type is location
     * 
     * @var string
     */
    protected $locationX;
    
    /**
     * The longitude of location, available when the message type is location
     * 
     * @var string
     */
    protected $locationY;
    
    /**
     * The scale of map, available when the message type is location
     * 
     * @var string
     */
    protected $scale;
    
    /**
     * The detail address of location, available when the message type is location
     * 
     * @var string 
     */
    protected $label;
    
    /**
     * The media id, available when the message type is voice
     * 
     * @var string
     */
    protected $mediaId;
    
    /**
     * The media format, available when the message type is voice
     * 
     * @var string
     */
    protected $format;
    
    /**
     * The HTTP raw post data, equals to $GLOBALS["HTTP_RAW_POST_DATA"] on default
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
     * Returns user input content
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
     * Returns the message id
     * 
     * @return string
     */
    public function getMsgId()
    {
        return $this->msgId;
    }
    
    /**
     * Returns the message type
     * 
     * Currently could be text, image, location, link or event
     * 
     * @return string
     */
    public function getMsgType()
    {
        return $this->msgType;
    }
    
    public function getPicUrl()
    {
        return $this->picUrl;
    }
    
    public function getLocationX()
    {
        return $this->locationX;
    }
    
    public function getLocationY()
    {
        return $this->locationY;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function getScale()
    {
        return $this->scale;
    }
    
    public function getMediaId()
    {
        return $this->mediaId;
    }
    
    public function getFormat()
    {
        return $this->format;
    }
    
    /**
     * Parse the user input message and response matched rule message
     * 
     * @return \Widget\Callback
     */
    public function __invoke()
    {
        // Check if it's requested from the WeChat server
        $echostr = $this->query('echostr');
        if ($this->checkSignature()) {
            echo $this->escape($echostr);
        } else {
            return $this->response('Not Found', '404');
        }

        // 解析用户输入
        $this->parsePostData();
        
        foreach ($this->rules as $rule) {
            switch ($rule['type']) {
                case 'has' :
                    if (false !== strpos($this->content, $rule['expected'])) {
                        $this->handle($rule['fn']);
                        break 2;
                    }
                    break;
                
                case 'is' :
                    if ($this->content == $rule['expected']) {
                        $this->handle($rule['fn']);
                        break 2;
                    }
                    break;
                
                case 'match' :
                    if (preg_match($rule['expected'], $this->content)) {
                        $this->handle($rule['fn']);
                        break 2;
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
    
    public function handle($fn)
    {
        $this->handled = true;
        $content = $fn($this, $this->widget);

        $this->responseMsg($content);
    }
    
    public function responseMsg($content)
    {
        if (!$content instanceof SimpleXMLElement) {
            $content = $this->sendText($content);
        }
        echo $content->asXml();
    }

    /**
     * Generate text message for response
     * 
     * @param string $content
     * @param bool $mark Whenter mark the message or not
     * @return \SimpleXMLElement
     */
    public function sendText($content, $mark = false)
    {
        $xml = $this->createXml();

        $this->addCDataChild($xml, 'Content', $content);
        
        return $this->send('text', $xml, $mark);
    }
    
    /**
     * Generate music message for response
     * 
     * @param string $title The title of music
     * @param string $description The description display blow the title
     * @param string $url The music URL for player
     * @param string $hqUrl The HQ music URL for player when user in WIFI
     * @param string $mark Whenter mark the message or not
     * @return \SimpleXMLElement
     */
    public function sendMusic($title, $description, $url, $hqUrl = null, $mark = false)
    {
        $xml    = $this->createXml();
        $music  = $xml->addChild('Music');
        
        $this
            ->addCDataChild($music, 'Title', $title)
            ->addCDataChild($music, 'Description', $description)
            ->addCDataChild($music, 'MusicUrl', $url)
            ->addCDataChild($music, 'HQMusicUrl', $hqUrl);

        return $this->send('music', $xml, $mark);    
    }
    
    /**
     * Generate article message for response
     * 
     * @param array $articles The article array
     * @param bool $mark Whenter mark the message or not
     * @return \SimpleXMLElement 
     */
    public function sendArticle(array $articles, $mark = false)
    {
        $xml = $this->createXml();

        // Convert single article array
        if (!is_int(key($articles))) {
            $articles = array($articles);
        }
        
        $xml->addChild('ArticleCount', count($articles));
        
        $items = $xml->addChild('Articles');
        foreach ($articles as $article) {
            $article += array(
                'title' => null,
                'description' => null,
                'picUrl' => null,
                'url' => null
            );
            $item = $items->addChild('item');
            $this
                ->addCDataChild($item, 'Title', $article['title'])
                ->addCDataChild($item, 'Description', $article['description'])
                ->addCDataChild($item, 'PicUrl', $article['picUrl'])
                ->addCDataChild($item, 'Url', $article['url']);
        }

        return $this->send('news', $xml, $mark);
    }
    
    /**
     * Generate message for response
     * 
     * @param string $type The type of message
     * @param \SimpleXMLElement $body The body of message
     * @param bool $mark $mark Whenter mark the message or not
     * @return \SimpleXMLElement
     */
    protected function send($type, SimpleXMLElement $xml, $mark = false)
    {
        $this
            ->addCDataChild($xml, 'ToUserName', $this->from)
            ->addCDataChild($xml, 'FromUserName', $this->to)
            ->addCDataChild($xml, 'MsgType', $type);
        
        $xml->addChild('CreateTime', time());
        $xml->addChild('FuncFlag', (int)$mark);
        
        return $xml;
    }

    /**
     * Create a root xml object 
     * 
     * @return \SimpleXMLElement
     */
    protected function createXml()
    {
        $xml = new SimpleXMLElement('<xml/>');
        
        return $xml;
    }
    
    protected function addCDataChild(SimpleXMLElement $xml, $name, $value)
    {
        $child = $xml->addChild($name);
        $node = dom_import_simplexml($child); 
        $node->appendChild($node->ownerDocument->createCDATASection($value));
        return $this;
    }
    
    /**
     * Adds rule to handle user input
     * 
     * @param string $type
     * @param string $expected
     * @param \Closure $fn
     * @return \Widget\Callback
     */
    protected function addRule($type, $expected, \Closure $fn)
    {
        $this->rules[] = array(
            'type' => $type,
            'expected' => $expected,
            'fn' => $fn
        );
        return $this;
    }
    
    /**
     * Parse post data to recive user OpenID and user input
     * 
     * @todo detect invald input
     */
    protected function parsePostData()
    {
        $fields = array(
            'text'      => array('Content'),
            'image'     => array('PicUrl'),
            'location'  => array('Location_X', 'Location_Y', 'Scale', 'Label'),
            'voice'     => array('MediaId', 'Format')
        );
        
        if ($this->postData) {
            $postObj        = simplexml_load_string($this->postData, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->from     = (string)$postObj->FromUserName;
            $this->to       = (string)$postObj->ToUserName;
            $this->msgId    = (string)$postObj->MsgId;
            $this->msgType  = (string)$postObj->MsgType;
            if (isset($fields[$this->msgType])) {
                foreach ($fields[$this->msgType] as $field) {
                    if (isset($postObj->$field)) {
                        $name = lcfirst(strtr($field, array('_' => '')));
                        $this->$name = (string)$postObj->$field;
                    }
                }
            }         
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