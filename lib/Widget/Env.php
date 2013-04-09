<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The environment widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Server $server The server widget
 */
class Env extends AbstractWidget
{
    /**
     * The env name
     * 
     * @var string
     */
    protected $env;
    
    protected $configDir = 'config/config_%env%.php';
    
    public function __construct(array $options = array())
    {
        parent::__construct($options + array(
            'env' => $this->env
        ));
  
        // Load configuration by env
        $file = str_replace('%env%', $this->env, $this->configDir);
        $this->loadConfigFile($file);

        // Load cli configuration when run in cli mode
        if (php_sapi_name() === 'cli') {
            $file = str_replace('%env%', 'cli', $this->configDir);
            $this->loadConfigFile($file);
        }
        
        // Load configuration for current developer
        $admin = current(explode('@', $this->server['SERVER_ADMIN']));
        if ($admin) {
            $file = str_replace('%env%', $admin, $this->configDir);
            $this->loadConfigFile($file);
        }
    }

    /**
     * Returns the env widget
     * 
     * @return \Widget\Env
     */
    public function __invoke()
    {
        return $this;
    }
    
    /**
     * Check if in the development development
     * 
     * @return bool
     */
    public function isDev()
    {
        return $this->env === 'dev';
    }
    
    /**
     * Check if in the test development
     * 
     * @return bool
     */
    public function isTest()
    {
        return $this->env === 'test';
    }
    
    /**
     * Check if in the production development
     * 
     * @return bool
     */
    public function isProd()
    {
        return $this->env === 'prod';
    }
    
    /**
     * Returns the env string
     * 
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }
    
    /**
     * Set application env
     * 
     * The application env value get from this order:
     * 
     *     $env > $_SERVER['APPLICATION_ENV'] > prod
     * 
     * @param string $env
     */
    public function setEnv($env)
    {
        if ($env) {
            $this->env = $env;
        } else {
            $this->env = $this->server['APPLICATION_ENV'] ?: 'prod';
        }
    }
    
    /**
     * Load widget config by specified file
     * 
     * @param string $file
     */
    public function loadConfigFile($file)
    {
        if (!is_file($file)) {
            return;
        }
        
        $cofnig = (array)require $file;
        $this->widget->config($cofnig);
    }
}