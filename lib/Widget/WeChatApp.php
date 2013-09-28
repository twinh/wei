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
     * @var string
     * @todo better name
     */
    protected $fallback;

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

    public function parse()
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
     * Parse the user input message and return matched rule message
     *
     * @return string|null
     */
    public function run()
    {
        if (!$this->valid) {
            return 'Forbidden';
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

            case  'event':
                $eventRule = $this->rules['event'];
                $event = strtolower($this->event);
                if (isset($eventRule[$event])
                    && isset($eventRule[$event][$this->eventKey])) {
                    return $this->handle($eventRule[$event][$this->eventKey]);
                }
                break;

            case 'location':
            case 'image':
            case 'voice':
            case 'video':
            case 'link':
                if (isset($this->rules[$this->msgType])) {
                    return $this->handle($this->rules[$this->msgType]);
                }
                break;
        }

        if (!$this->handled && $this->fallback) {
            return $this->handle($this->fallback);
        }
    }

    /**
     * Attach a callback which triggered when user subscribed you
     *
     * @param \Closure $fn
     * @return $this
     */
    public function subscribe(Closure $fn)
    {
        return $this->addEventRule('subscribe', null, $fn);
    }

    /**
     * Attach a callback which triggered when user unsubscribed you
     *
     * @param \Closure $fn
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
     * @param \Closure $fn
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
     * @param \Closure $fn
     * @return $this
     */
    public function is($keyword, Closure $fn)
    {
        return $this->addRule('is', $keyword, $fn);
    }

    /**
     * Attach a callback with a keyword, which triggered when user input contains the keyword
     *
     * @param string $keyword The keyword to search in user input
     * @param \Closure $fn
     * @return $this
     */
    public function has($keyword, Closure $fn)
    {
        return $this->addRule('has', $keyword, $fn);
    }

    /**
     * Attach a callback with a keyword, which triggered when user input starts with the keyword (case insensitive)
     *
     * @param string $keyword The keyword to search in user input
     * @param \Closure $fn
     * @return $this
     */
    public function startsWith($keyword, Closure $fn)
    {
        return $this->addRule('startsWith', $keyword, $fn);
    }

    /**
     * Attach a callback with a regex pattern which triggered when user input match the pattern
     *
     * @param string $pattern The pattern to match
     * @param \Closure $fn
     * @return $this
     */
    public function match($pattern, Closure $fn)
    {
        return $this->addRule('match', $pattern, $fn);
    }

    /**
     * Attach a callback to handle image message
     *
     * @param \Closure $fn
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
     * @param \Closure $fn
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
     * @param \Closure $fn
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
     *  Attach a callback which triggered when none of the rule handled the input
     *
     * @param \Closure $fn
     * @return boolean
     */
    public function fallback(Closure $fn)
    {
        $this->fallback = $fn;
        return $this;
    }

    /**
     * Generate text message for output
     *
     * @param string $content
     * @return \SimpleXMLElement
     */
    public function sendText($content)
    {
        $xml = $this->createXml();
        $this->addCDataChild($xml, 'Content', $content);
        return $this->send('text', $xml);
    }

    /**
     * Generate music message for output
     *
     * @param string $title The title of music
     * @param string $description The description display blow the title
     * @param string $url The music URL for player
     * @param string $hqUrl The HQ music URL for player when user in WIFI
     * @return \SimpleXMLElement
     */
    public function sendMusic($title, $description, $url, $hqUrl = null)
    {
        $xml    = $this->createXml();
        $music  = $xml->addChild('Music');

        $this
            ->addCDataChild($music, 'Title', $title)
            ->addCDataChild($music, 'Description', $description)
            ->addCDataChild($music, 'MusicUrl', $url)
            ->addCDataChild($music, 'HQMusicUrl', $hqUrl);

        return $this->send('music', $xml);
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
     * @return \SimpleXMLElement
     */
    public function sendArticle(array $articles)
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

        return $this->send('news', $xml);
    }

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

    protected function handle($fn)
    {
        $this->handled = true;

        $content = $fn($this, $this->widget);
        if (!$content instanceof SimpleXMLElement) {
            $content = $this->sendText($content);
        }

        return $content->asXML();
    }

    /**
     * Generate message for output
     *
     * @param string $type The type of message
     * @param \SimpleXMLElement $xml The xml object
     * @return \SimpleXMLElement
     */
    protected function send($type, SimpleXMLElement $xml)
    {
        $this
            ->addCDataChild($xml, 'ToUserName', $this->fromUserName)
            ->addCDataChild($xml, 'FromUserName', $this->toUserName)
            ->addCDataChild($xml, 'MsgType', $type);

        $xml->addChild('CreateTime', time());

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

    /**
     * Adds a cdata section to specified xml object
     *
     * @param \SimpleXMLElement $xml
     * @param string $name
     * @param string $value
     * @return $this
     */
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
     * @param string $keyword
     * @param \Closure $fn
     * @return $this
     */
    protected function addRule($type, $keyword, Closure $fn)
    {
        $this->rules['text'][] = array(
            'type' => $type,
            'keyword' => $keyword,
            'fn' => $fn
        );
        return $this;
    }

    protected function addEventRule($name, $key, Closure $fn)
    {
        $this->rules['event'][$name][$key] = $fn;
        return $this;
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

    protected function handleText()
    {
        foreach ($this->rules['text'] as $rule) {
            $matched = false;
            switch ($rule['type']) {
                case 'is':
                    $matched = $this->content == $rule['keyword'];
                    break;

                case 'has' :
                    $matched = false !== strpos($this->content, $rule['keyword']);
                    break;

                case 'startsWith':
                    if (0 === stripos($this->content, $rule['keyword'])) {
                        $matched = true;
                        $this->keyword = substr($this->content, strlen($rule['keyword']));
                    }
                    break;

                case 'match':
                    $matched = preg_match($rule['keyword'], $this->content);
                    break;
            }
            if ($matched) {
                return $this->handle($rule['fn']);
            }
        }
    }
}
