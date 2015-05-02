<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service to detect the environment name and load configuration by environment name
 *
 * The environment name detect order:
 *
 *     user defined $name > $detector callback > $ipMap
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Env extends Base
{
    /**
     * The environment name
     *
     * @var string
     */
    protected $name;

    /**
     * A callback to detect the environment name
     *
     * @var callable
     */
    protected $detector;

    /**
     * An associative array contains server ip and environment name pairs
     *
     * @var array
     */
    protected $ipMap = array(
        '127.0.0.1' => 'dev'
    );

    /**
     * The configuration file pattern
     *
     * @var string
     */
    protected $configFile = 'config/config-%env%.php';

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

        // Detect environment name if not set
        if (!$this->name) {
            $this->detectEnvName();
        }

        // Load configuration by environment name
        $this->loadConfigFile($this->configFile, $this->name);

        // Load CLI configuration when run in CLI mode
        if (php_sapi_name() === 'cli') {
            $this->loadConfigFile($this->configFile, 'cli');
        }
    }

    /**
     * Returns the environment name
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->name;
    }

    /**
     * Detect environment by server IP
     */
    public function detectEnvName()
    {
        // Return if env name is detected, or, or continue detecting by IP
        if ($this->detector && $this->name = call_user_func($this->detector)) {
            return;
        }

        // Executes in web server, like Apache, Nginx
        if (isset($this->server['SERVER_ADDR'])) {
            $ip = $this->server['SERVER_ADDR'];
            if (isset($this->ipMap[$ip])) {
                $this->name = $this->ipMap[$ip];
            } else {
                $this->name = 'prod';
            }
            return;
        }

        // Executes in CLI
        if (php_sapi_name() == 'cli' && $ips = $this->getServerIps()) {
            foreach ($ips as $ip) {
                if (isset($this->ipMap[$ip])) {
                    $this->name = $this->ipMap[$ip];
                    return;
                }
            }
        }

        $this->name = 'prod';
        return;
    }

    /**
     * Check if in specified environment
     *
     * @param string $env
     * @return bool
     */
    public function is($env)
    {
        return $this->name == $env || strpos($this->name, $env . '-') === 0;
    }

    /**
     * Check if in the development environment
     *
     * @return bool
     */
    public function isDev()
    {
        return $this->is('dev');
    }

    /**
     * Check if is the test environment
     *
     * @return bool
     */
    public function isTest()
    {
        return $this->is('test');
    }

    /**
     * Check if in the production environment
     *
     * @return bool
     */
    public function isProd()
    {
        return $this->is('prod');
    }

    /**
     * Returns the env string
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set application environment name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Loads configs from specified file to service container
     *
     * @param string $file The config file path
     * @param string $env The value to replace the %env% placeholder, default to the env name
     * @return $this
     */
    public function loadConfigFile($file, $env = null)
    {
        $file = str_replace('%env%', $env ?: $this->name, $file);
        if (is_file($file)) {
            $config = (array)require $file;
            $this->wei->setConfig($config);
        }
        return $this;
    }

    /**
     * Returns server IPs from `ifconfig` command line
     *
     * @return array
     * @todo windows
     */
    protected function getServerIps()
    {
        // TODO check command result: command not found, Permission denied
        preg_match_all('/eth(?:.+?)inet addr: ?([^ ]+)/s', `/sbin/ifconfig`, $ips);
        return $ips[1];
    }
}
