<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

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
    protected $token = 'wei';

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
     * The callback executes before send the XML data
     *
     * @var callable
     */
    protected $beforeSend;

    /**
     * The element values of post XML data
     *
     * Most of the available element names in post XML data
     * common  : MsgType, FromUserName, ToUserName, MsgId, CreateTime, Ticket
     * text    : Content
     * image   : PicUrl
     * location: Location_X, Location_Y, Scale, Label
     * voice   : MediaId, Format
     * event   : Event, EventKey
     * video   : MediaId, ThumbMediaId
     * link    : Title, Description
     *
     * @var array
     */
    protected $attrs = array();

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

        $this->parsePostData();
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
            return htmlspecialchars($this->query['echostr'], ENT_QUOTES, 'UTF-8');
        }

        switch ($this->getMsgType()) {
            case 'text' :
                if ($result = $this->handleText()) {
                    return $result;
                }
                break;

            case 'event':
                $eventRule = $this->rules['event'];
                $event = strtolower($this->getEvent());
                if (isset($eventRule[$event])
                    && isset($eventRule[$event][$this->getEventKey()])) {
                    return $this->handle($eventRule[$event][$this->getEventKey()]);
                }
                break;

            // including location, image, voice, video and link
            default:
                if (isset($this->rules[$this->getMsgType()])) {
                    return $this->handle($this->rules[$this->getMsgType()]);
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
     * Returns the XML element value
     *
     * @param string $name
     * @return mixed
     */
    public function getAttr($name)
    {
        return isset($this->attrs[$name]) ? $this->attrs[$name] : null;
    }

    /**
     * Returns all of XML element values
     *
     * @return array
     */
    public function getAttrs()
    {
        return $this->attrs;
    }

    /**
     * Returns the HTTP raw post data
     *
     * @return string
     */
    public function getPostData()
    {
        return $this->postData;
    }

    /**
     * Returns your user id
     *
     * @return string
     */
    public function getToUserName()
    {
        return $this->getAttr('ToUserName');
    }

    /**
     * Returns the user openID who sent message to you
     *
     * @return string
     */
    public function getFromUserName()
    {
        return $this->getAttr('FromUserName');
    }

    /**
     * Returns the timestamp when message created
     *
     * @return string
     */
    public function getCreateTime()
    {
        return $this->getAttr('CreateTime');
    }

    /**
     * Returns the user input string, available when the message type is text
     *
     * @return string
     */
    public function getContent()
    {
        return $this->getAttr('Content');
    }

    /**
     * Returns the message id
     *
     * @return string
     */
    public function getMsgId()
    {
        return $this->getAttr('MsgId');
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
        return $this->getAttr('MsgType');
    }

    /**
     * Returns the picture URL, available when the message type is image
     *
     * @return string
     */
    public function getPicUrl()
    {
        return $this->getAttr('PicUrl');
    }

    /**
     * Returns the latitude of location, available when the message type is location
     *
     * @return string
     */
    public function getLocationX()
    {
        return $this->getAttr('Location_X');
    }

    /**
     * Returns the longitude of location, available when the message type is location
     *
     * @return string
     */
    public function getLocationY()
    {
        return $this->getAttr('Location_Y');
    }

    /**
     * Returns the detail address of location, available when the message type is location
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->getAttr('Label');
    }

    /**
     * Returns the scale of map, available when the message type is location
     *
     * @return string
     */
    public function getScale()
    {
        return $this->getAttr('Scale');
    }

    /**
     * Returns the media id, available when the message type is voice or video
     *
     * @return string
     */
    public function getMediaId()
    {
        return $this->getAttr('MediaId');
    }

    /**
     * Returns the media format, available when the message type is voice
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->getAttr('Format');
    }

    /**
     * Returns the type of event, could be subscribe, unsubscribe or CLICK, available when the message type is event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->getAttr('Event');
    }

    /**
     * Returns the key value of custom menu, available when the message type is event
     *
     * @return string
     */
    public function getEventKey()
    {
        return $this->getAttr('EventKey');
    }

    /**
     * Returns the thumbnail id of video, available when the message type is video
     *
     * @return string
     */
    public function getThumbMediaId()
    {
        return $this->getAttr('ThumbMediaId');
    }

    /**
     * Returns the title of URL, available when the message type is link
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttr('Title');
    }

    /**
     * Returns the description of URL, available when the message type is link
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getAttr('Description');
    }

    /**
     * Returns the URL link, available when the message type is link
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getAttr('Url');
    }

    /**
     * Returns the ticket string, available when user scan from the QR Code
     *
     * @return string
     */
    public function getTicket()
    {
        return $this->getAttr('Ticket');
    }

    /**
     * Returns the text content or click event key
     *
     * @return bool|string
     */
    public function getKeyword()
    {
        if ($this->getMsgType() == 'text') {
            return strtolower($this->getContent());
        } elseif ($this->getMsgType() == 'event' && $this->getEvent() == 'click') {
            return strtolower($this->getEventKey());
        }
        return false;
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
            'ToUserName' => $this->getFromUserName(),
            'FromUserName' => $this->getToUserName(),
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
     * Parse post data to receive user OpenID and input content and message attr
     */
    protected function parsePostData()
    {
        // Check if the WeChat server signature is valid
        $query = $this->query;
        $tmpArr = array(
            $this->token,
            isset($query['timestamp']) ? $query['timestamp'] : '',
            isset($query['nonce']) ? $query['nonce'] : ''
        );
        sort($tmpArr, SORT_STRING);
        $tmpStr = sha1(implode($tmpArr));
        $this->valid = (isset($query['signature']) && $tmpStr === $query['signature']);

        // Parse the message data
        if ($this->valid && $this->postData) {
            // Do not output libxml error messages to screen
            $useErrors = libxml_use_internal_errors(true);
            $attrs = simplexml_load_string($this->postData, 'SimpleXMLElement', LIBXML_NOCDATA);
            libxml_use_internal_errors($useErrors);

            // Fix the issue that XML parse empty data to new SimpleXMLElement object
            $this->attrs = array_map('strval', (array)$attrs);
        }
    }

    /**
     * Handle text rule
     *
     * @return string|false
     */
    protected function handleText()
    {
        $content = $this->getContent();
        foreach ($this->rules['text'] as $rule) {
            if ($rule['type'] == 'is' && 0 === strcasecmp($content, $rule['keyword'])) {
                return $this->handle($rule['fn']);
            }

            if ($rule['type'] == 'has' && false !== mb_stripos($content, $rule['keyword'])) {
                return $this->handle($rule['fn']);
            }

            if ($rule['type'] == 'startsWith' && 0 === mb_stripos($content, $rule['keyword'])) {
                return $this->handle($rule['fn']);
            }

            if ($rule['type'] == 'match' && preg_match($rule['keyword'], $content)) {
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

        $content = $fn($this, $this->wei);
        if (!is_array($content)) {
            $content = $this->sendText($content);
        }

        $this->beforeSend && call_user_func_array($this->beforeSend, array($this, &$content, $this->wei));

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
