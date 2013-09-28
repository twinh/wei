<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A service to detect user OS, browser and device name and version
 *
 * @author      Twin Huang <twinhuang@qq.com>
 *
 * @method      bool isIe() Check if the user is browsing in Internet Explorer browser
 * @method      bool isChrome() Check if the user is browsing in Chrome browser
 * @method      bool isFirefox() Check if the user is browsing in Firefox browser
 *
 * @method      bool isIos()  Check if the device is running on Apple's iOS platform
 * @method      bool isAndroid() Check if the device is running on Google's Android platform
 * @method      bool isWindowsPhone() Check if the device is running on Windows Phone platform
 *
 * @method      bool isIphone() Check if the user is browsing by iPhone/iPod
 * @method      bool isIpad() Check if the user is browsing by iPad
 */
class Ua extends Base
{
    protected $patterns = array(
        // Browser
        'ie'            => 'MSIE ([\w.]+)',
        'chrome'        => 'Chrome\/([\w.]+)',
        'firefox'       => 'Firefox\/([\w.]+)',

        // OS
        'ios'           => 'iP(?:hone|ad).*OS ([\d_]+)', // Contains iPod
        'android'       => 'Android ([\w.]+)',
        'windowsphone'  => 'Windows Phone (?:OS )?([\w.]+)',

        // Device (Mobile & Tablet)
        'iphone'        => 'iPhone OS ([\d_]+)',
        'ipad'          => 'iPad.*OS ([\d_]+)'
    );

    /**
     * The versions of detected os
     *
     * @var string
     */
    protected $versions = array();

    /**
     * The user agent string from request header
     *
     * @var string
     */
    protected $userAgent;

    /**
     * The server and execution environment parameters, equals to $_SERVER on default
     *
     * @var array
     */
    protected $server;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if (!$this->server) {
            $this->server = &$_SERVER;
        }

        if (!$this->userAgent) {
            $this->userAgent = isset($this->server['HTTP_USER_AGENT']) ? $this->server['HTTP_USER_AGENT'] : null;
        }
    }

    /**
     * Check if in the specified browser, OS or device
     *
     * @param string $name The name of browser, OS or device
     * @return bool
     */
    public function __invoke($name)
    {
        return $this->is($name);
    }

    /**
     * Check if in the specified browser, OS or device
     *
     * @param string $name The name of browser, OS or device
     * @throws \InvalidArgumentException When name is not defined in patterns array
     * @return bool
     */
    public function is($name)
    {
        $name = strtolower($name);

        if (!array_key_exists($name, $this->patterns)) {
            throw new \InvalidArgumentException(sprintf('Unrecognized browser, OS, mobile or tablet name "%s"', $name));
        }

        if (preg_match('/' . $this->patterns[$name] . '/i', $this->userAgent, $matches)) {
            $this->versions[$name] = isset($matches[1]) ? $matches[1] : false;
            return true;
        } else {
            $this->versions[$name] = false;
            return false;
        }
    }

    /**
     * Returns the version of specified browser, OS or device
     *
     * @param string $name
     * @return string
     */
    public function getVersion($name)
    {
        $name = strtolower($name);
        if (!isset($this->versions[$name])) {
            $this->is($name);
        }
        return $this->versions[$name];
    }

    /**
     * Magic call for method isXXX
     *
     * @param string $name
     * @param array $args
     * @return bool
     */
    public function __call($name, $args)
    {
        if('is' == substr($name, 0, 2)) {
            return $this->is(substr($name, 2));
        }
        return parent::__call($name, $args);
    }

    /**
     * Check if the device is mobile
     *
     * @link https://github.com/serbanghita/Mobile-Detect
     * @license MIT License https://github.com/serbanghita/Mobile-Detect/blob/master/LICENSE.txt
     * @return bool
     */
    public function isMobile()
    {
        $s = $this->server;
        if (
            isset($s['HTTP_ACCEPT']) &&
                (strpos($s['HTTP_ACCEPT'], 'application/x-obml2d') !== false || // Opera Mini; @reference: http://dev.opera.com/articles/view/opera-binary-markup-language/
                 strpos($s['HTTP_ACCEPT'], 'application/vnd.rim.html') !== false || // BlackBerry devices.
                 strpos($s['HTTP_ACCEPT'], 'text/vnd.wap.wml') !== false ||
                 strpos($s['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') !== false) ||
            isset($s['HTTP_X_WAP_PROFILE'])             ||
            isset($s['HTTP_X_WAP_CLIENTID'])            ||
            isset($s['HTTP_WAP_CONNECTION'])            ||
            isset($s['HTTP_PROFILE'])                   ||
            isset($s['HTTP_X_OPERAMINI_PHONE_UA'])      || // Reported by Nokia devices (eg. C3)
            isset($s['HTTP_X_NOKIA_IPADDRESS'])         ||
            isset($s['HTTP_X_NOKIA_GATEWAY_ID'])        ||
            isset($s['HTTP_X_ORANGE_ID'])               ||
            isset($s['HTTP_X_VODAFONE_3GPDPCONTEXT'])   ||
            isset($s['HTTP_X_HUAWEI_USERID'])           ||
            isset($s['HTTP_UA_OS'])                     || // Reported by Windows Smartphones.
            isset($s['HTTP_X_MOBILE_GATEWAY'])          || // Reported by Verizon, Vodafone proxy system.
            isset($s['HTTP_X_ATT_DEVICEID'])            || // Seend this on HTC Sensation. @ref: SensationXE_Beats_Z715e
            //HTTP_X_NETWORK_TYPE = WIFI
            ( isset($s['HTTP_UA_CPU']) &&
                    $s['HTTP_UA_CPU'] == 'ARM'          // Seen this on a HTC.
            )
        ) {
            return true;
        }
        return false;
    }
}
