<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Escapes special characters for safe ouput
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Escape extends AbstractWidget
{
    /**
     * Escapes special characters for safe ouput
     *
     * @param string $input The input to be escaped
     * @param string $type The escape type
     * @param string $charset The charset to encoding input for HTML escape
     * @return string
     */
    public function __invoke($input, $type = 'html', $charset = 'UTF-8')
    {
        switch ($type) {
            case 'js':
                return $this->js($input);

            case 'html':
            default:
                return $this->html($input, $charset);
        }
    }

    /**
     * Escapes HTML data
     *
     * @param string $input The input to be escaped
     * @param string $charset The charset to encoding input for HTML escape
     * @return string
     */
    public function html($input, $charset = 'UTF-8')
    {
        return htmlspecialchars($input, ENT_QUOTES, $charset);
    }

    /**
     * Escapes javascript string
     *
     * @param string $input The input to be escaped
     * @return string
     */
    public function js($input)
    {
        return strtr($input, array(
            '\\' => '\\\\',
            "'" => "\\'",
            '"' => '\\"',
            "\r" => '\\r',
            "\n" => '\\n',
        ));
    }
}
