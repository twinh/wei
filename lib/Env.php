<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
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
     * A PHP file returns the environment name
     *
     * @var string
     */
    protected $envFile = '.env.php';

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
    protected $ipMap = [
        '127.0.0.1' => 'dev',
    ];

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
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct(array $options = [])
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
        if (\PHP_SAPI === 'cli') {
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
        // Detect from local env file
        if ($this->envFile && is_file($this->envFile)) {
            $this->name = require $this->envFile;
            if ($this->name) {
                return;
            }
        }

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
        if (\PHP_SAPI == 'cli' && $ips = $this->getServerIps()) {
            foreach ($ips as $ip) {
                if (isset($this->ipMap[$ip])) {
                    $this->name = $this->ipMap[$ip];
                    return;
                }
            }
        }

        $this->name = 'prod';
    }

    /**
     * Check if in specified environment
     *
     * @param string $env
     * @return bool
     */
    public function is($env)
    {
        return $this->name == $env || 0 === strpos($this->name, $env . '-');
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
        $config = $this->getFileConfig($file);
        if ($config) {
            $this->wei->setConfig($config);
        }
        return $this;
    }

    /**
     * Loads two config files in specified directory to service container
     *
     * @param string $dir The config directory
     * @param string $env
     * @return $this
     */
    public function loadConfigDir($dir, $env = null)
    {
        !$env && $env = $this->name;
        $config = $this->getFileConfig($dir . '/config.php');
        $envConfig = $this->getFileConfig($dir . '/config-' . $env . '.php');
        $config = array_replace_recursive($config, $envConfig);
        $this->wei->setConfig($config);
        return $this;
    }

    /**
     * Returns the config array return by file or empty array if file not found
     *
     * @param string $file
     * @return array
     */
    protected function getFileConfig($file)
    {
        if (is_file($file)) {
            return (array) require $file;
        } else {
            return [];
        }
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
        preg_match_all('/eth(?:.+?)inet addr: ?([^ ]+)/s', shell_exec('/sbin/ifconfig'), $ips);
        return $ips[1];
    }
}
