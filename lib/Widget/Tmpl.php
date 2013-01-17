<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Tmpl
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\View $view The view widget
 * @todo        Generate debug info
 * @todo        cache
 */
class Tmpl extends WidgetProvider
{
    /**
     * Options
     *
     * @var array
     */
    public $options = array(
        'start' => '<!-- START %s:%s -->',
        'end'   => '<!-- END %s:%s -->',
    );

    /**
     * The name of the template
     *
     * @var string
     */
    protected $name;

    /**
     * The stack to determine the order of blocks
     *
     * @var array
     */
    protected $blockStack = array();

    /**
     * The blocks in template file, define by $this->block('name') and $this->endblock()
     *
     * @var array
     */
    protected $blocks = array();

    /**
     * The stack to determine the order of layouts
     *
     * @var array
     */
    protected $layoutStack = array();

    /**
     * The current template name in render scope
     *
     * @var string
     */
    protected $current;

    /**
     * The newest blocks
     *
     * @var array
     */
    protected $freshBlocks = array();

    /**
     * Blocks to be replace
     *
     * @var array
     */
    protected $placeholders = array();

    /**
     * @var boolen
     */
    protected $debug = false;

    /**
     * Render the template
     *
     * @param  array  $context The variables pass to the template
     * @return string|null The rendered content
     * @todo check block stack
     */
    public function render($context = array())
    {
        extract($context, EXTR_OVERWRITE);

        $this->current = (string)$this->name;
        do {
            ob_start();
            require $this->view->getFile($this->current);

            if ($this->hasLayout($this->current)) {
                ob_end_clean();
            // Do not have layout, return the rendered content
            } else {
                return empty($this->placeholders) ? ob_get_clean() : $this->renderPlaceholders();
            }
       } while ($this->current = array_pop($this->layoutStack));
    }

    /**
     * Render the placeholder defined by $this->parent() and $this->load()
     *
     * @return string The rendered content
     */
    protected function renderPlaceholders()
    {
        $replace = array();
        foreach ($this->placeholders as $parents) {
            $replace[] = $this->blocks[$parents[0]][$parents[1]];
        }

        return str_replace(array_keys($this->placeholders), $replace, ob_get_clean());
    }

    /**
     * Define the template layout
     *
     * @param string $name The name of layout
     */
    protected function layout($name)
    {
        $this->layoutStack[$this->current] = (string)$name;
    }

    /**
     * Check if the template has layout
     *
     * @param  string $name The name of the template
     * @return boolean
     */
    protected function hasLayout($name)
    {
        return array_key_exists($name, $this->layoutStack);
    }

    /**
     * Define the start of the template block
     *
     * @param  string  $name The name of the block
     * @return boolean Whether the block need to be executed or not
     */
    protected function block($name)
    {
        $this->blockStack[] = (string)$name;

        $execute = true;
        $control = true;

        switch (true) {
            // If the block has been defined in placeholders, render it for
            // replacing placeholders
            case array_search(array($this->current, $name), $this->placeholders) :

            // If the current template is not the base layout, render it for the
            // parent or base layouts
            case $this->hasLayout($this->current) :
                break;

            // If we have the newest block content, return false to notice the
            // template do NOT execute it
            case isset($this->freshBlocks[$name]) :
                $execute = false;
                break;

            // I'm in the base layout and the children blocks look not like to
            // extend me, just execute it and output directly without output
            // control
            default :
                $control = false;
        }

        $control && ob_start();

        if ($this->debug) {
            echo sprintf($this->options['start'], $this->current, $name);
        }

        return $execute;
    }

    /**
     * Define the end of the template block
     *
     * @return void
     */
    protected function endblock()
    {
        $name = array_pop($this->blockStack);

        // add debug text
        if ($this->debug) {
            echo sprintf($this->options['end'], $this->current, $name);
        }

        // If we have the fresh block, delete current output buffer and output
        // the fresh one instead
        if (isset($this->freshBlocks[$name])) {
            $this->blocks[$this->current][$name] = ob_get_clean();
            echo $this->freshBlocks[$name];

            return;
        }

        // If the current template is not the base layout, save the output
        // buffer for parent and add it to the fresh blocks
        if ($this->hasLayout($this->current)) {
            $this->blocks[$this->current][$name] = ob_get_clean();
            $this->freshBlocks[$name] = $this->blocks[$this->current][$name];

            return;
        }

        // Also, save for placeholders
        if (array_search(array($this->current, $name), $this->placeholders)) {
            $this->blocks[$this->current][$name] = ob_get_clean();
            //return;
        }

        // The block has not been extended
    }

    /**
     * @see \Widget\Tmpl::render
     * @param array $context
     */
    public function display($context = array())
    {
        echo $this->render($context);
    }

    /**
     * Load parent block content
     *
     * @throws Exception When parent method does not be called in block
     *                   When current template do not have a parent layout
     */
    protected function parent()
    {
        if (empty($this->blockStack)) {
            list($file, $line) = $this->getCalledPosition();
            throw new Exception(sprintf('Method "parent" should be called between $this->block() and $this->endblock()'), 500, $file, $line);
        }

        if (!$this->hasLayout($this->current)) {
            list($file, $line) = $this->getCalledPosition();
            throw new Exception(sprintf('Template "%s" do not have a parent layout', $this->current), 500, $file, $line);
        }

        // TODO better way ?
        end($this->layoutStack);
        $layout = $this->layoutStack[key($this->layoutStack)];

        end($this->blockStack);
        $block = $this->blockStack[key($this->blockStack)];

        $placeholder = sprintf('<!--%s/%s-->', $layout, $block);

        $this->placeholders[$placeholder] = array($layout, $block);

        echo $placeholder;
    }

    /**
     *
     *
     * @param  string $var
     * @param  mixed  $default
     * @return mixed
     */
    public function defaults($var, $default = null)
    {
        return empty($var) ? $var : $default;
    }

    /**
     * Include a template
     *
     * @param string $name The name of template
     * @param  $context The variables pass to the template
     * @param array $context
     * @return mixed
     * @todo context scope
     */
    protected function import($name, $context = array())
    {
        return $this->view->display($name, $context);
    }

    /**
     * Get the previous php backtrace position
     *
     * @param  int   $level
     * @return array The file path and line
     * @todo detect call_user_func and call_user_func_array
     */
    protected function getCalledPosition($level = 1)
    {
        $traces = debug_backtrace();

        $file = isset($traces[$level]['file']) ? $traces[$level]['file'] : $traces[$level + 1]['file'];
        $line = isset($traces[$level]['line']) ? $traces[$level]['line'] : $traces[$level + 1]['line'];

        return array($file, $line);
    }

    /**
     * Load a templace block
     *
     * @param string     $name
     * @param integer $level
     * @todo refact placeholders
     */
    protected function load($name, $level = 0)
    {
        // todo
    }

    public function __invoke()
    {
        return $this;
    }
}
