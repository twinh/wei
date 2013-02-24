<?php

/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Escape
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Escape extends AbstractWidget
{
    /**
     * Escapes special characters for safe ouput
     *
     * @param string $data
     * @param string $type
     * @param string $charset
     * @return string
     */
    public function __invoke($data, $type = 'html', $charset = 'UTF-8')
    {
        switch ($type) {
            case 'js':
                return $this->js($data);

            case 'html':
            default:
                return $this->html($data, $charset);
        }
    }

    /**
     * Escapes html data
     *
     * @param string $data
     * @param string $charset
     * @return string
     */
    public function html($data, $charset = 'UTF-8')
    {
        return htmlspecialchars($data, ENT_QUOTES, $charset);
    }

    /**
     * Escapes javascript string
     *
     * @param string $data
     * @return string
     */
    public function js($data)
    {
        return strtr($data, array(
            '\\' => '\\\\',
            "'" => "\\'",
            '"' => '\\"',
            "\r" => '\\r',
            "\n" => '\\n',
        ));
    }
}
