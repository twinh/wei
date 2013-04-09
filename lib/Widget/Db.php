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
 * A container widget for Doctrine dbal connection object
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Db extends AbstractWidget
{
    /**
     * Doctrine DBAL connection object
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;

    /**
     * Constructor
     *
     * @param array $options
     * @link http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->conn = DriverManager::getConnection($this->getOption());
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
