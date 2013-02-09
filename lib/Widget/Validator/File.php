<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use Widget\UnexpectedTypeException;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class File extends AbstractRule
{
    protected $message = 'This value must be a valid file';
    
    protected $notFoundMessage = 'This value must be an existing file';
    
    protected $notReadableMessage = 'This file is not readable';
    
    protected $maxSizeMessage = 'This file is too large(%size%), allowed maximum size is %maxSize%';
    
    protected $minSizeMessage = 'This file is too small(%size%), expected minimum size is %minSize%';

    protected $extsMessage = 'This file extension(%ext%) is not allowed, allowed extension: %exts%';
    
    protected $excludeExtsMessage = 'This file extension(%ext%) is not allowed, not allowed extension: %excludeExts%';
    
    protected $mimeTypeNotDetectedMessage = 'This file mime type could not be detected';
    
    protected $mimeTypesMessage = 'This file mime type "%mimeType%" is not allowed';
    
    protected $excludeMimeTypesMessage = 'This file mime type "%mimeType%" is not allowed';
    
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
     * The allowed file extensions
     *
     * @var array
     */
    protected $exts = array();
        
    /**
     * The excluding file extensions
     *
     * @var array
     */
    protected $excludeExts = array();
    
    /**
     * The allowd file mime types
     * 
     * @var array
     */
    protected $mimeTypes = array();
    
    /**
     * The excluding file mime types
     * 
     * @var array
     */
    protected $excludeMimeTypes = array();
    
    /**
     * The file size unit
     * 
     * @var string
     */
    protected $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    /**
     * The magic database file to detect file mime type
     * 
     * @link http://www.php.net/manual/en/function.finfo-open.php
     * @var string|null
     */
    protected $magicFile;
    
    /**
     * Determime the object source is a file path, check with the include_path.
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

        // Validate file extension
        // Use substr instead of pathinfo, because pathinfo may return error value in unicode
        $ext = substr($file, strrpos($file, '.') + 1);
        if ($this->excludeExts && in_array($ext, $this->excludeExts)) {
            $this->addError('excludeExts', array(
                'ext'           => $ext,
                'excludeExts'   => implode(',', $this->excludeExts)
            ));
        }

        if ($this->exts && !in_array($ext, (array) $this->exts)) {
            $this->addError('exts', array(
                'ext'   => $ext,
                'exts'  => implode(',', (array) $this->exts)
            ));
        }

        // Validate file size
        $size = 0;
        if ($this->maxSize || $this->minSize) {
            if (!is_readable($file)) {
                $this->addError('notReadable');
                return false;
            } else {
                $size = filesize($file);
            }
        }
  
        if ($size && $this->maxSize && $this->maxSize <= $size) {
            $this->addError('maxSize', array(
                'size'      => $this->fromBytes($size),
                'maxSize'   => $this->fromBytes($this->maxSize)
            ));
        }
        
        if ($size && $this->minSize && $this->minSize > $size) {
            $this->addError('minSize', array(
                'size'      => $this->fromBytes($size),
                'minSize'   => $this->fromBytes($this->minSize)
            ));
        }
        
        // Validate file mime type
        if ($this->mimeTypes || $this->excludeMimeTypes) {
            $mimeType = $this->getMimeType($file);
            if (!$mimeType) {
                $this->addError('mimeTypeNotDetected');
                return false;
            }
        }

        if ($this->mimeTypes && !$this->inMimeType($mimeType, $this->mimeTypes)) {
            $this->addError('mimeTypes', array(
                'mimeType'  => $mimeType,
                'mimeTypes' => implode(',', $this->mimeTypes)
            ));
        }
        
        if ($this->excludeMimeTypes && $this->inMimeType($mimeType, $this->excludeMimeTypes)) {
            $this->addError('excludeMimeTypes', array(
                'mimeType'          => $mimeType,
                'excludeMimeTypes'  => implode(',', $this->excludeMimeTypes)
            ));
        }
        
        return !$this->errors;
    }

    /**
     * Checks if a mime type exists in a mime type array
     * 
     * @param string $findMe    The mime type to be searched
     * @param array $mimeTypes  The mime type array, allow element likes 
     *                          "image/*" to match all image mime type, such as 
     *                          "image/gif", "image/jpeg", etc
     * @return boolean
     */
    public function inMimeType($findMe, $mimeTypes)
    {
        foreach ($mimeTypes as $mimeType) {
            if ($mimeType == $findMe) {
                return true;
            }
            
            $type = strstr($mimeType, '/*', true);
            if ($type && $type === strstr($findMe, '/', true)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Set allowed file extensions
     * 
     * @param string|array $exts String format likes 'php,js' or array format likes [php, js]
     * @return \Widget\Validator\File
     */
    public function setExts($exts)
    {
        $this->exts = $this->convertToArray($exts);
        
        return $this;
    }
    
     /**
     * Set exclude file extensions
     * 
     * @param string|array $exts String format likes 'php,js' or array format likes [php, js]
     * @return \Widget\Validator\File
     */
    public function setExcludeExts($exts)
    {
        $this->excludeExts = $this->convertToArray($exts);
        
        return $this;
    }
    
    /**
     * Set maximum file size 
     * 
     * @param string|int $maxSize
     * @return \Widget\Validator\File
     */
    protected function setMaxSize($maxSize)
    {
        $this->maxSize = $this->toBytes($maxSize);
        
        return $this;
    }
    
    /**
     * Set the minimum file size
     * 
     * @param string|int $minSize
     * @return \Widget\Validator\File
     */
    protected function setMinSize($minSize)
    {
        $this->minSize = $this->toBytes($minSize);
        
        return $this;
    }
    
    /**
     * Converts human readable file size (e.g. 1.2MB, 10KB) into bytes
     * 
     * @param string|int $size
     * @return int
     */
    public function toBytes($size)
    {
        if (is_numeric($size)) {
            return (int) $size;
        }
        
        $unit = strtoupper(substr($size, -2));
        
        $value = substr($size, 0, -1);
        if (!is_numeric($value)) {
            $value = substr($value, 0, -1);
        }
        
        $exponent = array_search($unit, $this->units);
        return (int)($value * pow(1024, $exponent));
    }
    
    /**
     * Formats bytes to human readable file size (e.g. 1.2MB, 10KB)
     * 
     * @param int $bytes
     * @return string
     */
    public function fromBytes($bytes)
    {
        for ($i=0; $bytes >= 1024 && $i < 8; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . $this->units[$i];
    }
    
    /**
     * Set the file mime types
     * 
     * @param string|array $mimeTypes
     * @return \Widget\Validator\File
     */
    public function setMimeTypes($mimeTypes)
    {
        $this->mimeTypes = $this->convertToArray($mimeTypes);
        
        return $this;
    }
    
    /**
     * Set the file exclude mime types
     * 
     * @param string|array $excludeMimeTypes
     * @return \Widget\Validator\File
     */
    public function setExcludeMimeTypes($excludeMimeTypes)
    {
        $this->excludeMimeTypes = $this->convertToArray($excludeMimeTypes);
        
        return $this;
    }
    
    /**
     * Converts parameter to array
     * 
     * @param mixed $var
     * @return array
     * @throws UnexpectedTypeException When parameter is not a string or array
     */
    protected function convertToArray($var)
    {
        if (is_string($var)) {
            return explode(',', $var);
        } elseif (is_array($var)) {
            return $var;
        } else {
            throw new UnexpectedTypeException($var, 'string or array');
        }
    }
    
    /**
     * Returns the file mime type on success
     * 
     * @param string $file The file path
     * @return string|false
     */
    public function getMimeType($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE, $this->magicFile);
        if (!$finfo) {
            throw new \InvalidArgumentException('Failed to open fileinfo database');
        }
        return finfo_file($finfo, $file);
    }
}
