<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use \Closure;
use \SimpleXMLElement;

/**
 * A service handles WeChat(WeiXin) callback message
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://mp.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E6%8E%A5%E5%8F%A3%E6%8C%87%E5%8D%97
 */
class WeChatApp extends Base
{
    /**
     * The WeChat token to generate signature
     *
     * @var string
     */
    protected $token = 'widget';

    /**
     * The HTTP raw post data, equals to $GLOBALS['HTTP_RAW_POST_DATA'] on default
     *
     * @var string
     */
    protected $postData;

    /**
     * The URL query parameters, equals to $_GET on default
     *
     * @var array
     */
    protected $query;

    /**
     * The rules to generate output message
     *
     * @var array
     */
    protected $rules = array(
        'text'      => array(),
        'event'     => array(),
        'image'     => null,
        'location'  => null,
        'voice'     => null,
        'video'     => null,
        'link'      => null
    );

    /**
     * A handler executes when none of rules handled the input
     *
     * @var callable
     */
    protected $defaults;

    /**
     * Whether the signature is valid
     *
     * @var bool
     */
    protected $valid = false;

    /**
     * Are there any callbacks handled the message ?
     *
     * @var bool
     */
    protected $handled = false;

    /**
     * The callback executes before send the xml data
     *
     * @var callable
     */
    protected $beforeSend;

    /**
     * Available when the matched the "startsWith" rule
     *
     * @var string
     */
    protected $keyword;

    protected $fromUserName;

    protected $toUserName;

    protected $createTime;

    protected $msgId;

    protected $msgType;

    protected $content;

    protected $picUrl;

    protected $locationX;

    protected $locationY;

    protected $scale;

    protected $label;

    protected $mediaId;

    protected $format;

    protected $event;

    protected $eventKey;

    protected $thumbMediaId;

    protected $title;

    protected $description;

    protected $url;

    /**
     * Constructor
     *
     * @param array $options
     * @global string $GLOBALS['HTTP_RAW_POST_DATA']
     */
    public function __construct($options = array())
    {
        parent::__construct($options);

        if (!$this->query) {
            $this->query = &$_GET;
        }

        if (is_null($this->postData) && isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            $this->postData = $GLOBALS['HTTP_RAW_POST_DATA'];
        }

        $this->parse();
    }

    /**
     * Start up WeChat application and output the matched rule message
     *
     * @return $this
     */
    public function __invoke()
    {
        echo $this->run();
        return $this;
    }

    /**
     * Execute the matched rule and returns the rule result
     *
     * Returns false when the token is invalid or no rules matched
     *
     * @return string|false
     */
    public function run()
    {
        // The token is invalid
        if (!$this->valid) {
            return false;
        }

        // Output 'echostr' for fist time authentication
        if (isset($this->query['echostr'])) {
            return htmlspecialchars($this->query['echostr'], \ENT_QUOTES, 'UTF-8');
        }

        switch ($this->msgType) {
            case 'text' :
                if ($result = $this->handleText()) {
                    return $result;
                }
                break;

            case 'event':
                $eventRule = $this->rules['event'];
                $event = strtolower($this->event);
                if (isset($eventRule[$event])
                    && isset($eventRule[$event][$this->eventKey])) {
                    return $this->handle($eventRule[$event][$this->eventKey]);
                }
                break;

            // including location, image, voice, video, link
            default:
                if (isset($this->rules[$this->msgType])) {
                    return $this->handle($this->rules[$this->msgType]);
                }
        }

        // Fallback to the default rule
        if (!$this->handled && $this->defaults) {
            return $this->handle($this->defaults);
        }

        return false;
    }

    /**
     * Check if the request is verify the token string
     *
     * @return bool
     */
    public function isVerifyToken()
    {
        return isset($this->query['echostr']);
    }

    /**
     * Attach a callback which triggered when user subscribed you
     *
     * @param Closure $fn
     * @return $this
     */
    public function subscribe(Closure $fn)
    {
        return $this->addEventRule('subscribe', null, $fn);
    }

    /**
     * Attach a callback which triggered when user unsubscribed you
     *
     * @param Closure $fn
     * @return $this
     */
    public function unsubscribe(Closure $fn)
    {
        return $this->addEventRule('unsubscribe', null, $fn);
    }

    /**
     * Attach a callback which triggered when user click the custom menu
     *
     * @param string $key The key of event
     * @param Closure $fn
     * @return $this
     */
    public function click($key, Closure $fn)
    {
        return $this->addEventRule('click', $key, $fn);
    }

    /**
     * Attach a callback which triggered when user input equals to the keyword
     *
     * @param string $keyword The keyword to compare with user input
     * @param Closure $fn
     * @return $this
     */
    public function is($keyword, Closure $fn)
    {
        return $this->addTextRule('is', $keyword, $fn);
    }

    /**
     * Attach a callback with a keyword, which triggered when user input contains the keyword
     *
     * @param string $keyword The keyword to search in user input
     * @param Closure $fn
     * @return $this
     */
    public function has($keyword, Closure $fn)
    {
        return $this->addTextRule('has', $keyword, $fn);
    }

    /**
     * Attach a callback with a keyword, which triggered when user input starts with the keyword (case insensitive)
     *
     * @param string $keyword The keyword to search in user input
     * @param Closure $fn
     * @return $this
     */
    public function startsWith($keyword, Closure $fn)
    {
        return $this->addTextRule('startsWith', $keyword, $fn);
    }

    /**
     * Attach a callback with a regex pattern which triggered when user input match the pattern
     *
     * @param string $pattern The pattern to match
     * @param Closure $fn
     * @return $this
     */
    public function match($pattern, Closure $fn)
    {
        return $this->addTextRule('match', $pattern, $fn);
    }

    /**
     * Attach a callback to handle image message
     *
     * @param Closure $fn
     * @return $this
     */
    public function receiveImage(Closure $fn)
    {
        $this->rules['image'] = $fn;
        return $this;
    }

    /**
     * Attach a callback to handle location message
     *
     * @param Closure $fn
     * @return $this
     */
    public function receiveLocation(Closure $fn)
    {
        $this->rules['location'] = $fn;
        return $this;
    }

    /**
     * Attach a callback to handle voice message
     *
     * @param Closure $fn
     * @return $this
     */
    public function receiveVoice(Closure $fn)
    {
        $this->rules['voice'] = $fn;
        return $this;
    }

    /**
     * Attach a callback to handle video message
     *
     * @param Closure $fn
     * @return $this
     */
    public function receiveVideo(Closure $fn)
    {
        $this->rules['video'] = $fn;
        return $this;
    }

    /**
     * Attach a callback to handle link message
     *
     * @param Closure $fn
     * @return $this
     */
    public function receiveLink(Closure $fn)
    {
        $this->rules['link'] = $fn;
        return $this;
    }

    /**
     * Attach a handler which executes when none of the rule handled the input
     *
     * @param Closure $fn
     * @return boolean
     */
    public function defaults(Closure $fn)
    {
        $this->defaults = $fn;
        return $this;
    }

    /**
     * Generate text message for output
     *
     * @param string $content
     * @return array
     */
    public function sendText($content)
    {
        return $this->send('text', array(
            'Content' => $content
        ));
    }

    /**
     * Generate music message for output
     *
     * @param string $title The title of music
     * @param string $description The description display blow the title
     * @param string $url The music URL for player
     * @param string $hqUrl The HQ music URL for player when user in WIFI
     * @return array
     */
    public function sendMusic($title, $description, $url, $hqUrl = null)
    {
        return $this->send('music', array(
            'Music' => array(
                'Title' => $title,
                'Description' => $description,
                'MusicUrl' => $url,
                'HQMusicUrl' => $hqUrl
            )
        ));
    }

    /**
     * Generate article message for output
     *
     * ```
     * // Sends one article
     * $app->sendArticle(array(
     *     'title' => 'The title of article',
     *     'description' => 'The description of article',
     *     'picUrl' => 'The picture URL of article',
     *     'url' => 'The URL link to of article'
     * ));
     *
     * // Sends two or more articles
     * $app->sendArticle(array(
     *     array(
     *         'title' => 'The title of article',
     *         'description' => 'The description of article',
     *         'picUrl' => 'The picture URL of article',
     *         'url' => 'The URL link to of article'
     *     ),
     *     array(
     *         'title' => 'The title of article',
     *         'description' => 'The description of article',
     *         'picUrl' => 'Te picture URL of article',
     *         'url' => 'The URL link to of article'
     *     ),
     *     // more...
     *  ));
     * ```
     *
     * @param array $articles The article array
     * @return array
     */
    public function sendArticle(array $articles)
    {
        // Convert single article array
        if (!is_int(key($articles))) {
            $articles = array($articles);
        }

        $response = array(
            'ArticleCount' => count($articles),
            'Articles' => array(
                'item' => array()
            )
        );

        foreach ($articles as $article) {
            $article += array(
                'title' => null,
                'description' => null,
                'picUrl' => null,
                'url' => null
            );
            $response['Articles']['item'][] = array(
                'Title' => $article['title'],
                'Description' => $article['description'],
                'PicUrl' => $article['picUrl'],
                'Url' => $article['url']
            );
        }

        return $this->send('news', $response);
    }

    /**
     * Returns if the token is valid
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Returns the keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Returns your user id
     *
     * @return string
     */
    public function getToUserName()
    {
        return $this->toUserName;
    }

    /**
     * Returns the user openID who sent message to you
     *
     * @return string
     */
    public function getFromUserName()
    {
        return $this->fromUserName;
    }

    /**
     * Returns the timestamp when message created
     *
     * @var string
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Returns the user input string, available when the message type is text
     *
     * @var string
     */
    public function getContent()
    {
        return $this->content;
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
     * Currently could be text, image, location, link, event, voice, video
     *
     * @return string
     */
    public function getMsgType()
    {
        return $this->msgType;
    }

    /**
     * Returns the picture URL, available when the message type is image
     *
     * @var string
     */
    public function getPicUrl()
    {
        return $this->picUrl;
    }

    /**
     * Returns the latitude of location, available when the message type is location
     *
     * @return string
     */
    public function getLocationX()
    {
        return $this->locationX;
    }

    /**
     * Returns the longitude of location, available when the message type is location
     *
     * @return string
     */
    public function getLocationY()
    {
        return $this->locationY;
    }

    /**
     * Returns the detail address of location, available when the message type is location
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Returns the scale of map, available when the message type is location
     *
     * @return string
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Returns the media id, available when the message type is voice or video
     *
     * @return string
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }

    /**
     * Returns the media format, available when the message type is voice
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Returns the type of event, could be subscribe, unsubscribe or CLICK, available when the message type is event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Returns the key value of custom menu, available when the message type is event
     *
     * @return string
     */
    public function getEventKey()
    {
        return $this->eventKey;
    }

    /**
     * Returns the thumbnail id of video, available when the message type is video
     *
     * @return string
     */
    public function getThumbMediaId()
    {
        return $this->thumbMediaId;
    }

    /**
     * Returns the title of URL, available when the message type is link
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the description of URL, available when the message type is link
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the URL link, available when the message type is link
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Generate message for output
     *
     * @param string $type The type of message
     * @param array $response The response content
     * @return array
     */
    protected function send($type, array $response)
    {
        return $response + array(
            'ToUserName' => $this->fromUserName,
            'FromUserName' => $this->toUserName,
            'MsgType' => $type,
            'CreateTime' => time()
        );
    }

    /**
     * Adds a rule to handle user text input
     *
     * @param string $type
     * @param string $keyword
     * @param Closure $fn
     * @return $this
     */
    protected function addTextRule($type, $keyword, Closure $fn)
    {
        $this->rules['text'][] = array(
            'type' => $type,
            'keyword' => $keyword,
            'fn' => $fn
        );
        return $this;
    }

    /**
     * Adds a rule to handle user event, such as click, subscribe
     *
     * @param string $name
     * @param string $key
     * @param Closure $fn
     * @return $this
     */
    protected function addEventRule($name, $key, Closure $fn)
    {
        $this->rules['event'][$name][$key] = $fn;
        return $this;
    }

    /**
     * Parse request data
     */
    protected function parse()
    {
        // Check if it's requested from the WeChat server
        if ($this->checkSignature()) {
            $this->valid = true;
            $this->parsePostData();
        } else {
            $this->valid = false;
        }
    }

    /**
     * Parse post data to receive user OpenID and input content and more
     */
    protected function parsePostData()
    {
        $defaults = array('FromUserName', 'ToUserName', 'MsgId', 'CreateTime');
        $attrs = array(
            'text'      => array('Content'),
            'image'     => array('PicUrl'),
            'location'  => array('Location_X', 'Location_Y', 'Scale', 'Label'),
            'voice'     => array('MediaId', 'Format'),
            'event'     => array('Event', 'EventKey'),
            'video'     => array('MediaId', 'ThumbMediaId'),
            'link'      => array('Title', 'Description', 'Url')
        );

        if ($this->postData) {
            $postObj        = @simplexml_load_string($this->postData, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->msgType  = isset($postObj->MsgType) ? (string)$postObj->MsgType : null;
            if (isset($attrs[$this->msgType])) {
                foreach (array_merge($defaults, $attrs[$this->msgType]) as $field) {
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
        $query = $this->query;
        $tmpArr = array(
            $this->token,
            isset($query['timestamp']) ? $query['timestamp'] : '',
            isset($query['nonce']) ? $query['nonce'] : ''
        );
        sort($tmpArr);
        $tmpStr = sha1(implode($tmpArr));
        return isset($query['signature']) && $tmpStr === $query['signature'];
    }

    /**
     * Handle text rule
     *
     * @return string|false
     */
    protected function handleText()
    {
        foreach ($this->rules['text'] as $rule) {
            if ($rule['type'] == 'is' && 0 === strcasecmp($this->content, $rule['keyword'])) {
                return $this->handle($rule['fn']);
            }

            if ($rule['type'] == 'has' && false !== mb_stripos($this->content, $rule['keyword'])) {
                return $this->handle($rule['fn']);
            }

            if ($rule['type'] == 'startsWith' && 0 === mb_stripos($this->content, $rule['keyword'])) {
                $this->keyword = substr($this->content, strlen($rule['keyword']));
                return $this->handle($rule['fn']);
            }

            if ($rule['type'] == 'match' && preg_match($rule['keyword'], $this->content)) {
                return $this->handle($rule['fn']);
            }
        }
        return false;
    }

    /**
     * Executes callback handler
     *
     * @param Closure $fn
     * @return string
     */
    protected function handle($fn)
    {
        $this->handled = true;

        $content = $fn($this, $this->widget);
        if (!is_array($content)) {
            $content = $this->sendText($content);
        }

        $this->beforeSend && call_user_func($this->beforeSend, $this, $content, $this->widget);

        return $this->arrayToXml($content)->asXML();
    }

    /**
     * Convert to XML element
     *
     * @param array $array
     * @param SimpleXMLElement $xml
     * @return SimpleXMLElement
     */
    protected function arrayToXml(array $array, SimpleXMLElement $xml = null)
    {
        if ($xml === null) {
            $xml = new SimpleXMLElement('<xml/>');
        }
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if (isset($value[0])) {
                    foreach ($value as $subValue) {
                        $subNode = $xml->addChild($key);
                        $this->arrayToXml($subValue, $subNode);
                    }
                } else {
                    $subNode = $xml->addChild($key);
                    $this->arrayToXml($value, $subNode);
                }
            } else {
                // Wrap cdata for non-numeric string
                if (is_numeric($value)) {
                    $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node = dom_import_simplexml($child);
                    $node->appendChild($node->ownerDocument->createCDATASection($value));
                }
            }
        }
        return $xml;
    }
}
