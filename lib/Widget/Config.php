<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget to manage widget configurations
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Config extends Base
{
    /**
     * Convert configuration data to JSON
     *
     * @param string $name The name of configuration item
     * @return string
     */
    public function toJson($name)
    {
        return json_encode($this->widget->getConfig($name));
    }

    /**
     * Convert configuration data to HTML select options
     *
     * @param string $name The name of configuration item
     * @return string
     */
    public function toOptions($name)
    {
        $html = '';
        foreach ($this->widget->getConfig($name) as $value => $text) {
            if (is_array($text)) {
                $html .= '<optgroup label="' . $value . '">';
                foreach ($text as $v => $t) {
                    $html .= '<option value="' . $v . '">' . $t . '</option>';
                }
                $html .= '</optgroup>';
            } else {
                $html .= '<option value="' . $value . '">' . $text . '</option>';
            }
        }
        return $html;
    }

    /**
     * Adds callback to widget configuration
     *
     * @param string $name
     * @param callable $fn
     * @return $this
     */
    public function add($name, $fn)
    {
        $prevFn = $this->widget->getConfig($name);
        $this->widget->setConfig($name, !$prevFn ? $fn : function() use($fn, $prevFn) {
            $args = func_get_args();
            if (false === call_user_func_array($prevFn, $args)) {
                return false;
            } else {
                return call_user_func_array($fn, $args);
            }
        });
        return $this;
    }
}
