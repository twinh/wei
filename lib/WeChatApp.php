<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use Closure;
use SimpleXMLElement;

/**
 * A service handles WeChat(WeiXin) callback message
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://mp.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E6%8E%A5%E5%8F%A3%E6%8C%87%E5%8D%97
 */
class WeChatApp extends Base
{
    protected const RANDOM_STR_LENGTH = 16;

    private const MAX_PAD_VALUE = 32;

    /**
     * A string to generate signature
     *
     * @var string
     */
    protected $token = 'wei';

    /**
     * The App ID of WeChat account
     *
     * @var string
     */
    protected $appId;

    /**
     * A 43 length string to encrypt message
     *
     * @var string
     */
    protected $encodingAesKey;

    /**
     * The HTTP raw post data
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
    protected $rules = [
        'text' => [],
        'event' => [],
        'image' => null,
        'location' => null,
        'voice' => null,
        'video' => null,
        'link' => null,
    ];

    /**
     * A handler executes when none of rules handled the input
     *
     * @var callable
     */
    protected $defaults;

    /**
     * The message parse result
     *
     * @var array
     */
    protected $parseRet = ['code' => 1, 'message' => '解析成功'];

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
    protected $attrs = [];

    /**
     * Constructor
     *
     * @param array $options
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct($options = [])
    {
        parent::__construct($options);

        if (!$this->query) {
            // phpcs:ignore MySource.PHP.GetRequestData.SuperglobalAccessed
            $this->query = &$_GET;
        }

        if (null === $this->postData) {
            $this->postData = file_get_contents('php://input');
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
     * Parse post data to message attributes
     *
     * @return array
     */
    public function parse()
    {
        if (!$this->checkToken()) {
            return $this->parseRet = ['code' => -1, 'message' => 'Token不正确'];
        }

        $attrs = $this->xmlToArray($this->postData);
        if ($this->isEncrypted()) {
            $ret = $this->decryptMsg($attrs['Encrypt']);
            if (1 !== $ret['code']) {
                return $this->parseRet = $ret;
            }
            $attrs = $this->xmlToArray($ret['xml']);
        }

        $this->attrs = $attrs;
        return $this->parseRet = ['code' => 1, 'message' => '解析成功'];
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
        // The token or post data is invalid
        if (1 !== $this->parseRet['code']) {
            return false;
        }

        // Output 'echostr' for first time authentication
        if (isset($this->query['echostr'])) {
            return htmlspecialchars($this->query['echostr'], \ENT_QUOTES, 'UTF-8');
        }

        $msg = $this->handleMsg();
        if (!$msg) {
            return 'success';
        }
        return $this->isEncrypted() ? $this->encryptMsg($msg) : $msg;
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
     * Check if the message is encrypted
     *
     * @return bool
     */
    public function isEncrypted()
    {
        return isset($this->query['encrypt_type']) && 'aes' == $this->query['encrypt_type'];
    }

    /**
     * Attach a callback which triggered when transfer to customer service
     *
     * @param Closure $fn
     * @return $this
     */
    public function transferCustomer(Closure $fn)
    {
        $this->rules['transferCustomer'] = $fn;
        return $this;
    }

    /**
     * Attach a callback which triggered when user subscribed you
     *
     * @param Closure $fn
     * @return $this
     */
    public function subscribe(Closure $fn)
    {
        return $this->on('subscribe', $fn);
    }

    /**
     * Attach a callback which triggered when user unsubscribed you
     *
     * @param Closure $fn
     * @return $this
     */
    public function unsubscribe(Closure $fn)
    {
        return $this->on('unsubscribe', $fn);
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
        return $this->on('click', $key, $fn);
    }

    /**
     * Attach a callback which triggered when user scan the QR Code
     *
     * @param Closure $fn
     * @return $this
     */
    public function scan(Closure $fn)
    {
        return $this->on('scan', $fn);
    }

    /**
     * Adds a rule to handle user event, such as click, subscribe
     *
     * @param string $name
     * @param Closure|string $key
     * @param Closure $fn
     * @return $this
     */
    public function on($name, $key, Closure $fn = null)
    {
        $name = strtolower($name);
        if ($key instanceof Closure) {
            $this->rules['event'][$name][''] = $key;
        } else {
            $this->rules['event'][$name][$key] = $fn;
        }
        return $this;
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
     * @return bool
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
        return $this->send('text', [
            'Content' => $content,
        ]);
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
        return $this->send('music', [
            'Music' => [
                'Title' => $title,
                'Description' => $description,
                'MusicUrl' => $url,
                'HQMusicUrl' => $hqUrl,
            ],
        ]);
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
            $articles = [$articles];
        }

        $response = [
            'ArticleCount' => count($articles),
            'Articles' => [
                'item' => [],
            ],
        ];

        foreach ($articles as $article) {
            $article += [
                'title' => null,
                'description' => null,
                'picUrl' => null,
                'url' => null,
            ];
            $response['Articles']['item'][] = [
                'Title' => $article['title'],
                'Description' => $article['description'],
                'PicUrl' => $article['picUrl'],
                'Url' => $article['url'],
            ];
        }

        return $this->send('news', $response);
    }

    /**
     * Generate message to transfer to customer service
     *
     * @param array $data
     * @return array
     */
    public function sendTransferCustomerService($data = [])
    {
        return $this->send('transfer_customer_service', $data);
    }

    /**
     * Returns if the token is valid
     *
     * @return bool
     */
    public function isValid()
    {
        return 1 === $this->parseRet['code'];
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
     * Returns the scene id from the scan result, available when the message event is subscribe or scan
     *
     * @return string
     */
    public function getScanSceneId()
    {
        $eventKey = $this->getEventKey();
        if (0 === strpos($eventKey, 'qrscene_')) {
            $eventKey = substr($eventKey, 8);
        }
        return $eventKey;
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
     * Returns the user inputted content or clicked button value
     *
     * @return bool|string
     */
    public function getKeyword()
    {
        if ('text' == $this->getMsgType()) {
            return strtolower($this->getContent());
        } elseif ('event' == $this->getMsgType() && 'click' == strtolower($this->getEvent())) {
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
    public function send($type, array $response)
    {
        return $response + [
                'ToUserName' => $this->getFromUserName(),
                'FromUserName' => $this->getToUserName(),
                'MsgType' => $type,
                'CreateTime' => time(),
            ];
    }

    /**
     * @param string $text
     * @param string $encodingAesKey
     * @param string $appId
     * @return string
     */
    public function prpcryptEncrypt($text, $encodingAesKey, $appId)
    {
        $key = base64_decode($encodingAesKey . '=', true);

        // 获得16位随机字符串，填充到明文之前
        $random = $this->getRandomStr();
        $text = $random . pack('N', strlen($text)) . $text . $appId;
        $iv = substr($key, 0, 16);
        $text = $this->pkcs7Encode($text);
        return openssl_encrypt($text, 'AES-256-CBC', substr($key, 0, 32), \OPENSSL_ZERO_PADDING, $iv);
    }

    /**
     * Check if the WeChat server signature is valid
     */
    protected function checkToken()
    {
        $query = $this->query;
        $signature = $this->sign(
            $this->token,
            isset($query['timestamp']) ? $query['timestamp'] : '',
            isset($query['nonce']) ? $query['nonce'] : ''
        );
        return isset($query['signature']) && $signature === $query['signature'];
    }

    /**
     * Executes the matched rule and returns the rule result
     *
     * Returns false when the token is invalid or no rules matched
     *
     * phpcs:disable Generic.Metrics.NestingLevel.TooHigh
     *
     * @return string|false
     */
    protected function handleMsg()
    {
        switch ($this->getMsgType()) {
            case 'text':
                if ($result = $this->handleText()) {
                    return $result;
                }
                break;

            case 'event':
            case 'device_event':
                $event = strtolower($this->getEvent());
                switch ($event) {
                    case 'subscribe':
                        $result = $this->handleEvent('subscribe');
                        $scanResult = null;
                        if ($this->getTicket()) {
                            $scanResult = $this->handleEvent('scan');
                        }
                        if ($result) {
                            return $result;
                        }
                        if ($scanResult) {
                            return $scanResult;
                        }
                        break;

                    case 'scan':
                        if ($result = $this->handleEvent('scan')) {
                            return $result;
                        }
                        break;

                    default:
                        if ($result = $this->handleEvent($event, $this->getEventKey())) {
                            return $result;
                        }
                        break;
                }
                break;

            // Including location, image, voice, video and link
            default:
                if (isset($this->rules[$this->getMsgType()])) {
                    return $this->handle($this->rules[$this->getMsgType()]);
                }
        }

        // Check if enable to transfer to customer service
        if (isset($this->rules['transferCustomer'])) {
            return $this->handle($this->rules['transferCustomer']);
        }

        // Fallback to the default rule
        if (!$this->handled && $this->defaults) {
            return $this->handle($this->defaults);
        }

        return false;
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
        $this->rules['text'][] = [
            'type' => $type,
            'keyword' => $keyword,
            'fn' => $fn,
        ];
        return $this;
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
            if ('is' == $rule['type'] && 0 === strcasecmp($content, $rule['keyword'])) {
                return $this->handle($rule['fn']);
            }

            if ('has' == $rule['type'] && false !== mb_stripos($content, $rule['keyword'])) {
                return $this->handle($rule['fn']);
            }

            if ('startsWith' == $rule['type'] && 0 === mb_stripos($content, $rule['keyword'])) {
                return $this->handle($rule['fn']);
            }

            if ('match' == $rule['type'] && preg_match($rule['keyword'], $content)) {
                return $this->handle($rule['fn']);
            }
        }
        return false;
    }

    protected function handleEvent($event, $eventKey = false)
    {
        if (false !== $eventKey) {
            if (isset($this->rules['event'][$event][$eventKey])) {
                return $this->handle($this->rules['event'][$event][$eventKey]);
            }
        } else {
            if (isset($this->rules['event'][$event])) {
                return $this->handle(end($this->rules['event'][$event]));
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

        // Converts string to array
        $content = $fn($this, $this->wei) ?: '';
        if ($content && !is_array($content)) {
            $content = $this->sendText($content);
        }
        $this->beforeSend && call_user_func_array($this->beforeSend, [$this, &$content, $this->wei]);

        return $content ? $this->arrayToXml($content)->asXML() : '';
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
        if (null === $xml) {
            $xml = new SimpleXMLElement('<xml/>');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
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
        $array = simplexml_load_string($xml, 'SimpleXMLElement', \LIBXML_NOCDATA);
        libxml_use_internal_errors($useErrors);

        // Fix the issue that XML parse empty data to new SimpleXMLElement object
        return array_map('strval', (array) $array);
    }

    /**
     * 对密文进行解密
     *
     * @param string $encrypted 需要解密的密文
     * @param string $encodingAesKey
     * @param string $appId
     * @return array
     */
    protected function prpcryptDecrypt($encrypted, $encodingAesKey, $appId)
    {
        try {
            $key = base64_decode($encodingAesKey . '=', false);
            $iv = substr($key, 0, 16);
            $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', substr($key, 0, 32), \OPENSSL_ZERO_PADDING, $iv);
        } catch (\Exception $e) {
            return ['code' => -2002, 'message' => 'AES解密失败', 'e' => (string) $e];
        }

        try {
            // 去除补位字符
            $result = $this->pkcs7Decode($decrypted);
            // 去除16位随机字符串,网络字节序和AppId
            if (strlen($result) < self::RANDOM_STR_LENGTH) {
                return ['code' => -2003, 'message' => '解密后结果不能小于16位', 'result' => $result];
            }
            $content = substr($result, 16, strlen($result));
            $lenList = unpack('N', substr($content, 0, 4));
            $xmlLen = $lenList[1];
            $xml = substr($content, 4, $xmlLen);
            $fromAppId = substr($content, $xmlLen + 4);
        } catch (\Exception $e) {
            return ['code' => -2004, 'message' => '解密后得到的buffer非法', 'e' => (string) $e];
        }

        if ($fromAppId != $appId) {
            return ['code' => -2005, 'message' => 'AppId 校验错误', 'appId' => $appId, 'fromAppId' => $fromAppId];
        }

        return ['code' => 1, 'message' => '解密成功', 'xml' => $xml];
    }

    /**
     * @param string $replyMsg
     * @return string
     */
    protected function encryptMsg($replyMsg)
    {
        $encrypt = $this->prpcryptEncrypt($replyMsg, $this->encodingAesKey, $this->appId);
        $signature = $this->sign($encrypt, $this->token, $this->query['timestamp'], $this->query['nonce']);

        $xml = $this->arrayToXml([
            'Encrypt' => $encrypt,
            'MsgSignature' => $signature,
            'TimeStamp' => $this->query['timestamp'],
            'Nonce' => $this->query['nonce'],
        ])->asXML();
        return $xml;
    }

    /**
     * @param string $encrypt
     * @return array
     */
    protected function decryptMsg($encrypt)
    {
        $signature = $this->sign($encrypt, $this->token, $this->query['timestamp'], $this->query['nonce']);
        if ($signature != $this->query['msg_signature']) {
            return ['code' => -2001, 'message' => '签名验证错误'];
        }

        $ret = $this->prpcryptDecrypt($encrypt, $this->encodingAesKey, $this->appId);
        if (1 !== $ret['code']) {
            return $ret;
        }

        return ['code' => 1, 'message' => '解密成功', 'xml' => $ret['xml']];
    }

    /**
     * Generate the signature
     *
     * @param array $arr
     * @return string
     */
    protected function sign(...$arr)
    {
        sort($arr, \SORT_STRING);
        $str = implode('', $arr);
        return sha1($str);
    }

    /**
     * Generate random string
     *
     * @return string 生成的字符串
     */
    protected function getRandomStr()
    {
        $str = '';
        $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < static::RANDOM_STR_LENGTH; ++$i) {
            $str .= $strPol[mt_rand(0, $max)];
        }
        return $str;
    }

    /**
     * 对需要加密的明文进行填充补位
     *
     * @param string $text 需要进行填充补位操作的明文
     * @return string 补齐明文字符串
     */
    protected function pkcs7Encode($text)
    {
        $size = 32;
        $textLength = strlen($text);

        // 计算需要填充的位数
        $amountToPad = $size - ($textLength % $size);
        if (0 == $amountToPad) {
            $amountToPad = $size;
        }

        // 获得补位所用的字符
        $padChr = chr($amountToPad);
        $tmp = '';
        for ($index = 0; $index < $amountToPad; ++$index) {
            $tmp .= $padChr;
        }
        return $text . $tmp;
    }

    /**
     * 对解密后的明文进行补位删除
     *
     * @param string $text 解密后的明文
     * @return string 删除填充补位后的明文
     */
    protected function pkcs7Decode($text)
    {
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > static::MAX_PAD_VALUE) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }
}
