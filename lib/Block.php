<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that stores view content for template inheritance
 *
 * When a template uses inheritance and if you want to print a block multiple times, use the block function:
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Block extends Base
{
    /**
     * An array stores all block contents
     *
     * @var array
     */
    protected $data = array();

    /**
     * The name of current block
     *
     * @var string
     */
    protected $name;

    /**
     * The merge strategy of current block, could be "append", "prepend" or "set"
     *
     * @var string
     */
    protected $type;

    /**
     * Start to capture a block
     *
     * @param string $name
     * @param string $type
     * @return string
     * @throws \Exception When block type is unsupported
     */
    public function __invoke($name, $type = 'append')
    {
        if (!in_array($type, array('append', 'prepend', 'set'))) {
            throw new \Exception(sprintf('Unsupported block type "%s"', $type));
        }

        $this->name = $name;
        $this->type = $type;
        if (!isset($this->data[$this->name])) {
            $this->data[$this->name] = '';
        }
        ob_start();
        return '';
    }

    /**
     * Alias of __invoke method
     *
     * @param string $name
     * @param string $type
     * @return string
     * @throws \Exception
     */
    public function start($name, $type = 'append')
    {
        return $this->__invoke($name, $type);
    }

    /**
     * Store current block content
     *
     * @return string
     */
    public function end()
    {
        $content = ob_get_clean();
        switch ($this->type) {
            case 'append' :
                $this->data[$this->name] .= $content;
                break;

            case 'prepend' :
                $this->data[$this->name] = $content . $this->data[$this->name];
                break;

            case 'set' :
                $this->data[$this->name] = $content;
        }
        return '';
    }

    /**
     * Returns the content of specified block name
     *
     * @param string $name
     * @return string
     */
    public function get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
}