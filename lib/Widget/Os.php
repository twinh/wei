<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * A widget to detect user OS and browser name and version
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request A widget that handles the HTTP request data
 */
class Os extends AbstractWidget
{
    /**
     * Whether in chrome browser
     *
     * @var bool
     */
    public $chrome = false;

    /**
     * Whether in webkit browser
     *
     * @var bool
     */
    public $webkit = false;

    /**
     * Whether in opera browser
     *
     * @var bool
     */
    public $opera = false;

    /**
     * Whether in internet explorer browser
     *
     * @var bool
     */
    public $msie = false;

    /**
     * Whether in firefox browser
     *
     * @var bool
     */
    public $mozilla = false;

    /**
     * Whether in safari browser
     *
     * @var bool
     */
    public $safari = false;

    /**
     * The name of browser
     * 
     * @var string
     */
    protected $browser;

    /**
     * The version of browser
     *
     * @var string
     */
    protected $version;
    
    /**
     * The user agent string from request header
     * 
     * @var string
     */
    protected $ua;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->ua = $this->request->getServer('HTTP_USER_AGENT');

        $this->detect();
    }

    /**
     * Returns the name of browser
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->browser;
    }

    /**
     * Detect the browser name and version
     *
     * @link http://api.jquery.com/jQuery.browser
     * @copyright Copyright 2012 jQuery Foundation and other contributors
     * @license http://jquery.org/license
     */
    public function detect()
    {
        $ua = strtolower($this->ua);
        $matches = array();

        preg_match('/(chrome)[ \/]([\w.]+)/', $ua, $matches) ||
            preg_match('/(webkit)[ \/]([\w.]+)/', $ua, $matches) ||
            preg_match('/(opera)(?:.*version|)[ \/]([\w.]+)/', $ua, $matches) ||
            preg_match('/(msie) ([\w.]+)/', $ua, $matches) ||
            false === strpos($ua, 'compatible') && preg_match('/(mozilla)(?:.*? rv:([\w.]+)|)/', $ua, $matches);
        
        if (empty($matches)) {
            $matches = array('', '', 0);
        }

        // Ignore the first element
        list(, $this->browser, $this->version) = $matches;

        $this->browser && $this->{$this->browser} = true;

        // Chrome is Webkit, but Webkit is also Safari.
        if ($this->chrome) {
            $this->webkit = true;
        } elseif ($this->webkit) {
            $this->safari = true;
        }
    }
    
    /**
     * Returns the version of browser
     * 
     * @return string
     */
    public function getBrowser()
    {
        return $this->browser;
    }
    
    /**
     * Returns the version of browser
     * 
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * Check if the device is mobile
     * 
     * @link https://github.com/serbanghita/Mobile-Detect
     * @license MIT License https://github.com/serbanghita/Mobile-Detect/blob/master/LICENSE.txt
     * @return bool
     */
    public function inMobile()
    {
        $s = $this->request->getParameterReference('server');
        
        if (
            isset($s['HTTP_ACCEPT']) &&
                (strpos($s['HTTP_ACCEPT'], 'application/x-obml2d') !== false || // Opera Mini; @reference: http://dev.opera.com/articles/view/opera-binary-markup-language/
                 strpos($s['HTTP_ACCEPT'], 'application/vnd.rim.html') !== false || // BlackBerry devices.
                 strpos($s['HTTP_ACCEPT'], 'text/vnd.wap.wml') !== false ||
                 strpos($s['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') !== false) ||
            isset($s['HTTP_X_WAP_PROFILE'])             || // @todo: validate
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
    
    /**
     * Check if the user is browsing by iPhone/iPod
     * 
     * @return bool
     */
    public function inIphone()
    {
        return (bool)preg_match('/(iPhone\sOS)\s([\d_]+)/', $this->ua);
    }
    
    /**
     * Check if the user is browsing by iPad
     * 
     * @return bool
     */
    public function inIpad()
    {
        return (bool)preg_match('/(iPad).*OS\s([\d_]+)/', $this->ua);
    }
    
    /**
     * Check if the device is running on Apple's iOS platform
     * 
     * @return bool
     */
    public function inIOs()
    {
        return $this->inIPhone() || $this->inIPad();
    }
    
    /**
     * Check if the device is running on Google's Android platform
     * 
     * @return bool
     */
    public function inAndroid()
    {
        return preg_match('/(Android)\s+([\d.]+)/', $this->ua)
            || preg_match('/Silk-Accelerated/', $this->ua);
    }
    
    /**
     * Check if the device is running on Windows Phone platform
     * 
     * @return bool
     */
    public function inWindowsPhone()
    {
        return (bool)preg_match('/Windows Phone OS|XBLWP7|ZuneWP7/', $this->ua);
    }
}
