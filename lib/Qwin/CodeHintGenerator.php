<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Code hint generator
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class CodeHintGenerator extends Widget
{    
    public $options = array(
        'target' => 'doc/qwin-code-hint.php',
        'exclusions' => false,
        'withWidgetMap' => false, 
    );

    protected $classTmpl = 
'<?php
namespace Qwin;

throw new \Exception(\'The file "\' . __FILE__ . \'" is used for code hint only! Do NOT use for developing!\');

class Widget implements Widgetable
{
%s
}';
    protected $propertyTmpl = 
'    /**
     * @var %s
     */ 
    public $%s;';
    
    protected $methodTmpl = 
'    public function %s(%s)
    {
        $%s = new %s;
        return $%s->__invoke();
    }
 ';

    /**
     * Generate the widget code hint file
     * 
     * @param array $options
     * @return \Qwin\CodeHintGenerator
     */
    public function __invoke(array $options = array())
    {
        $this->option($options);
        $options = &$this->options;
        
        $content = '';
        
        // generate the custom widgets
        if ($options['withWidgetMap']) {
            foreach ($this->widgetManager->option('widgetMap') as $widget => $class) {
                if ($this->isExcludeWidget($widget)) {
                    continue;
                }
                $content .= $this->generateWidgetCodeHint($widget, $class);
            }
        }

        // generate the base widgets
        foreach (glob(__DIR__ . '/*.php') as $file) {
            $widget = basename($file, '.php');

            if ($this->isExcludeWidget($widget)) {
                continue;
            }

            $content .= $this->generateWidgetCodeHint($widget, '\Qwin\\' . $widget);
        }

        // save file
        $dir = dirname($options['target']);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        file_put_contents($options['target'], sprintf($this->classTmpl, $content));
        
        return $this;
    }
    
    public function generateWidgetCodeHint($name, $class)
    {
        if (!class_exists($class)) {
            $this->log(sprintf('Widget "%s" (Class "%s") not found', $name, $class));
            return false;
        }

        $reflection = new \ReflectionClass($class);

        if (!$reflection->hasMethod('__invoke')) {
            $this->log(sprintf('Method "__invoke" not found in class "%s"', $class));
            return false;
        }

        $invokeMethod = $reflection->getMethod('__invoke');

        $methodContent = $this->generateMethodDocComment($invokeMethod)
            . PHP_EOL . $this->generateMethodBody($invokeMethod, $name, $class);

        return $this->generatePropertyBody($class, $name) . PHP_EOL . PHP_EOL 
            . $methodContent . PHP_EOL;
    }
    
    /**
     * Generate the property body
     * 
     * @param type $widgetClass the full class of widget
     * @param array $widgetName the name of widget
     * @return string
     */
    public function generatePropertyBody($widgetClass, $widgetName)
    {
        $widgetName[0] = strtolower($widgetName[0]);
        return $protityContent = sprintf($this->propertyTmpl, $widgetClass, $widgetName);
    }

    /**
     * Generate the method doc comment
     * 
     * @param \ReflectionMethod $method
     * @return string
     */
    public function generateMethodDocComment(\ReflectionMethod $method)
    {
        return '    ' . $method->getDocComment();
    }
    
    /**
     * Generate the class method body
     * 
     * @param \ReflectionMethod $method
     * @param string $widgetName the name of the widget
     * @return string string
     */
    protected function generateMethodBody(\ReflectionMethod $method, $widgetName, $fullClass)
    {
        // generate parameters definition
        $parameterDefinition = '';
        $parameters = $method->getParameters();
        $count = count($parameters) - 1;
        
        /* @var $parameter \ReflectionParameter */
        foreach ($method->getParameters() as $i => $parameter) {
            $parameterDefinition .= '$' . $parameter->getName();
            
            if ($parameter->isDefaultValueAvailable()) {
                $parameterDefinition .= ' = ' . $this->exportVar($parameter->getDefaultValue());
            }
            
            if ($count != $i) {
                $parameterDefinition .= ', ';
            }
        }
        
        $lowerName = $widgetName;
        $lowerName[0] = strtolower($lowerName[0]);
        
        return sprintf($this->methodTmpl, $lowerName, $parameterDefinition, $lowerName, $fullClass, $lowerName);
    }
    
    /**
     * Returns a parsable string representation of a variable, and the empty
     * "array()" would present in one line
     * 
     * @param mixed $var 
     * @return string
     */
    protected function exportVar($var)
    {
        if (array() === $var) {
            return 'array()';
        }
        
        return var_export($var, true);
    }
    
    /**
     * Set exclusions option
     * 
     * @param array|string $widgets the exclusion widget list
     * @return \Qwin\CodeHintGenerator
     */
    public function setExclusionsOption($widgets)
    {
        if (!empty($widgets) && is_string($widgets)) {
            $exclusions = explode(',', $widgets);
        } elseif (is_array($widgets)) {
            $exclusions = $widgets;
        } else {
            $exclusions = array();
        }
        
        array_walk($exclusions, function(&$value){
            $value = strtolower(trim($value));
        });
        
        $this->options['exclusions'] = $exclusions;
        
        return $this;
    }
    
    /**
     * Check if the widget is in exclusion list
     * 
     * @param string $widget the name of widget
     * @return boolen
     */
    public function isExcludeWidget($widget)
    {
        return in_array(strtolower($widget), $this->options['exclusions']);
    }
}
