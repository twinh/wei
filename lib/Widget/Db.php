<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Doctrine\DBAL\DriverManager;

/**
 * Doctrine dbal connection
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Db extends WidgetProvider
{
    /**
     * Doctrine DBAL connection object
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;

    /**
     * The first parameter for DriverManager::getConnection
     *
     * @var array
     */
    protected $params = array();

    /**
     * Constructor
     *
     * @param array $options first parameters for DriverManager::getConnection
     * @see http://docs.doctrine-project.org/projects/doctrine-dbal/en/2.0.x/reference/configuration.html
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->conn = DriverManager::getConnection($this->params);
    }

    /**
     * Returns the Doctrine DBAL connection object
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function __invoke()
    {
        return $this->conn;
    }
}
