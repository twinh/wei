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
    
    protected $indent = '    ';

    protected $propertyTmpl = 
'    /**
     * @var %s
     */ 
    public $%s;';
    
    protected $classTmpl = 
'<?php
namespace Qwin;

throw new \Exception(\'The file "\' . __FILE__ . \'" is used for code hint only! Do NOT use for developing!\');

class Widget implements Widgetable
{
%s
}';
    
    protected $methodTmpl = 
'    public function %s(%s)
    {
        $%s = new \Qwin\%s;
        return $%s->__invoke();
    }
 ';

    public function __invoke(array $options = array())
    {
        $this->option($options);
        $options = &$this->options;
        
        $content = '';

        foreach (glob(__DIR__ . '/*.php') as $file) {
            $widgetName = basename($file, '.php');

            if (in_array(strtolower($widgetName), $this->options['exclusions'])) {
                continue;
            }
            
            $widgetClass = '\Qwin\\' . $widgetName;
             
            require_once $file;
            
            if (!class_exists($widgetClass, false)) {
                $this->log(sprintf('Widget "%s" (Class "%s") not found', $widgetName, $widgetClass));
                continue;
            }
            
            $reflection = new \ReflectionClass($widgetClass);
            
            if (!$reflection->hasMethod('__invoke')) {
                $this->log(sprintf('Method "__invoke" not found in class "%s"', $widgetClass));
                continue;
            }
            
            $invokeMethod = $reflection->getMethod('__invoke');

            $methodContent = $this->generateMethodDocComment($invokeMethod) . PHP_EOL . $this->generateMethodBody($invokeMethod, $widgetName);

            $content .= $this->generatePropertyBody($widgetClass, $widgetName) . PHP_EOL . PHP_EOL . $methodContent . PHP_EOL;
        }
        
        // save file
        $dir = dirname($options['target']);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        file_put_contents($options['target'], sprintf($this->classTmpl, $content));
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
        return $this->indent . $method->getDocComment();
    }
    
    /**
     * Generate the class method body
     * 
     * @param \ReflectionMethod $method
     * @param string $widgetName the name of the widget
     * @return string string
     */
    protected function generateMethodBody(\ReflectionMethod $method, $widgetName)
    {
        $lowerName = $widgetName;
        $lowerName[0] = strtolower($lowerName[0]);

        $parameterDefinition = '';
        
        // generate parameters definition
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
        
        return sprintf($this->methodTmpl, $lowerName, $parameterDefinition, $lowerName, $widgetName, $lowerName);
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
}