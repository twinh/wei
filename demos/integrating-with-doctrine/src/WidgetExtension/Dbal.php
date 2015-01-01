<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace WeiExtension;

use Doctrine\DBAL\DriverManager;
use Wei\Base;

/**
 * A container wei for Doctrine dbal connection object
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Dbal extends Base
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
