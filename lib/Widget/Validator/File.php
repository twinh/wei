<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class File extends AbstractRule
{
    protected $message = 'This value must be an valid file';
    
    protected $notFoundMessage = 'This value must be an existing file';
    
    protected $maxSizeMessage = 'This file is too large({{ size }}), allowed maximum size is {{ maxSize }}';
    
    protected $minSizeMessage = 'This file is too small({{ size }}), allowed minimum size is {{ minSize }}';
    
    protected $whiteExtsMessage = 'This file\'s extension({{ ext }}) is not allowed, allowed {{ whiteExts }}';
    
    protected $blackExtsMessage = 'This file\'s extension({{ ext }}) is not allowed, not allowed {{ blackExts }}';
    
    /**
     * The max file size limit
     *
     * @var int
     */
    protected $maxSize = 0;

    /**
     * The min file size limit
     *
     * @var int
     */
    protected $minSize = 0;

    /**
     * The white extensions list
     *
     * @var array
     */
    protected $whiteExts = array();

    /**
     * The black extensions list
     *
     * @var array
     */
    protected $blackExts = array();
    
    /**
     * Determine the object source is a file path, check with the include_path.
     *
     * @return string|bool
     */
    public function __invoke($file, $options = array())
    {
        $this->errors = array();
        $options && $this->option($options);
        
        if (!is_string($file) || empty($file)) {
            return false;
        }
        
        // Check directly if it's absolute path
        if ('/' == $file[0] || '\\' == $file[0] || (isset($file[1]) && ':' == $file[1])) {
            if (!is_file($file)) {
                $this->addError('notFound');
                return false;
            }
        }
        
        if (!stream_resolve_include_path($file)) {
            $this->addError('notFound');
            return false;
        }
        
        // Use substr instead of pathinfo, because pathinfo may return error value in unicode
        $ext = substr($file, strrpos($file, '.') + 1);
        if ($this->blackExts && in_array($ext, (array) $this->blackExts)) {
            $this->addError('blackExts', array(
                'ext' => $ext,
                'blackExts' => implode(',', (array) $this->blackExts)
            ));
        }

        if ($this->whiteExts && !in_array($ext, (array) $this->whiteExts)) {
            $this->addError('whiteExts', array(
                'ext' => $ext,
                'whiteExts' => implode(',', (array) $this->whiteExts)
            ));
        }

        // Size
        $size = 0;
        if ($this->maxSize || $this->minSize) {
            if (!is_readable($file)) {
                $this->addError('notReadable');
            } else {
                $size = filesize($file);
            }
        }
  
        if ($size && $this->maxSize && $this->maxSize <= $size) {
            $this->addError('maxSize', array(
                'size' => $size,
                'maxSize' => $this->maxSize
            ));
        }
        
        if ($size && $this->minSize && $this->minSize > $size) {
            $this->addError('minSize', array(
                'size' => $size,
                'minSize' => $this->minSize
            ));
        }
        
        return !$this->errors;
    }
}
