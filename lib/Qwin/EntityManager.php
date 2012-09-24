<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * EntityManager
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
use Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager as DoctrineEntityManager,
    Doctrine\Common\Cache\Cache;

class EntityManager extends Widget
{
    /**
     * Options for configuration
     * 
     * @var array
     * @see \Doctrine\ORM\Configuration
     */
    public $options = array(
        'cache' => null,
        'proxyDir' => null,
        'proxyNamespace' => null,
        'autoGenerateProxyClasses' => null,
        'annotationDriverPaths' => array(),
        'entityNamespaces' => array(),
    );

    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $options = &$this->options;
        
        // create cache object
        switch (true) {
            case is_string($options['cache']) :
                $class = '\Doctrine\Common\Cache\\' . $options['cache'];
                $cache = new $class;
                break;
                
             case $options['cache'] instanceof Cache :
                 $cache = $options['cache'];
                 break;
             
             default :
                 $cache = false;
        }
        
        // TODO as a widget ?
        $config = new Configuration;
        
        // set default cache
        if ($cache) {
            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);
            $config->setHydrationCacheImpl($cache);
            $config->setResultCacheImpl($cache);
        }

        // todo other driver ?
        if ($options['annotationDriverPaths']) {
            //$driver = new \Doctrine\Common\Annotations\Annotation\
            //$driverImpl = new \Doctrine\ORM\Mapping\Driver\YamlDriver('bin');
            $driverImpl = $config->newDefaultAnnotationDriver($options['annotationDriverPaths']);
            $config->setMetadataDriverImpl($driverImpl);
        }
        
        if ($options['entityNamespaces']) {
            $config->setEntityNamespaces($options['entityNamespaces']);
        }
        
        // Proxy configuration
        if ($options['proxyDir']) {
            $config->setProxyDir($options['proxyDir']);
        }
        
        if ($options['proxyNamespace']) {
            $config->setProxyNamespace($options['proxyNamespace']);
        }
        
        if (is_bool($options['autoGenerateProxyClasses'])) {
            $config->setAutoGenerateProxyClasses($options['autoGenerateProxyClasses']);
        }

        // Create EntityManager
        $this->em = DoctrineEntityManager::create($this->db(), $config);
    }
    
    /**
     * Get Docrine ORM entity manager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function __invoke()
    {
        return $this->em;
    }
}