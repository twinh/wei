<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Upload
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Post $post The post widget
 * @todo        Use other various instead of global various $_FILES
 * @todo        Add service widget and extend it
 * @todo        language
 * @todo        display size in result message
 */
class Upload extends WidgetProvider
{
    /**
     * $_FILES do not contain the key "$this->name" 
     */
    const ERR_NO_FILE           = 20;
    
    /**
     * File not valid for function is_uploaded_file
     */
    const ERR_NOT_UPLOADED_FILE = 21;
    
    /**
     * File size large than $this->maxSize
     */
    const ERR_FILE_TOO_LARGE    = 22;
    
    /**
     * File size samll than $this->minSize
     */
    const ERR_FILE_TOO_SAMLL    = 23;
    
    /**
     * File extension in black list
     */
    const ERR_EXT_NOT_ALLOW     = 24;
    
    /**
     * File extension not in white list
     */
    const ERR_EXT_NOT_IN_ALLOW  = 25;
    
    /**
     * Seems that the total file size is larger than the max size of post data
     * 
     * @link http://php.net/manual/en/ini.core.php#ini.post-max-size
     */
    const ERR_POST_SIZE = 26;
    
    /**
     * Can not move uploaded file
     */
    const ERR_MOVE_FAIL = 27;
    
    /**
     * The name defined in the file input, if it's not specified, use the first
     * key in $_FILES
     * 
     * @var string
     */
    protected $name;
    
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
    protected $minSize = 1;

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
     * Custome file name without extension to save
     * 
     * @var string 
     */
    protected $fileName;
    
    /**
     * The diretory to save file, if not exist, will try to create it
     * 
     * @var string 
     */
    protected $dir = 'uploads';
    
    /**
     * The full file path to save file, NOT IMPLEMENTED YET
     * 
     * @var string 
     */
    protected $to;

    /**
     * The return result list
     * 
     * @var array
     */
    protected $results = array(
        UPLOAD_ERR_OK           => 'File uploaded', 
        UPLOAD_ERR_INI_SIZE     => 'File too large', // File larger than upload_max_filesize, 
        UPLOAD_ERR_FORM_SIZE    => 'File too large', // File larger than MAX_FILE_SIZE defiend in html form
        UPLOAD_ERR_PARTIAL      => 'Partial file uploaded, please try again', 
        UPLOAD_ERR_NO_FILE      => 'No file uploaded', 
        UPLOAD_ERR_NO_TMP_DIR   => 'No temporary directory', 
        UPLOAD_ERR_CANT_WRITE   => 'Can\'t write to disk',
        UPLOAD_ERR_EXTENSION    => 'File upload stopped by extension',
        20                      => 'No file uploaded',
        21                      => 'No file uploaded',
        22                      => 'File too large', // File larger than $this->maxSize
        23                      => 'File too samll',
        24                      => 'File extension not allowed',
        25                      => 'File extension not in allowed list',
        26                      => 'Seems that the total file size is larger than the max size of post data, please check the size of your file',
        27                      => 'Can not move uploaded file',
    );
    
    /**
     * Upload a file
     * 
     * @param array $options
     * @return array
     * @todo divide to serval samll valid methods
     */
    public function __invoke(array $options = array())
    {
        $this->option($options);
        
        $files = $this->getFiles();
        
        // Set default name
        if (!$this->name) {
            $this->setName(null);
        }

        // Check if has file uploaded or file too large
        if (!isset($files[$this->name])) {
            if (empty($files) && !$this->post->toArray()) {
                return $this->result(static::ERR_POST_SIZE); 
            } else {
                return $this->result(static::ERR_NO_FILE);
            }
        }
        
        $file = $files[$this->name];
        
        // Handle UPLOAD_ERR_* error
        if (0 !== $file['error']) {
            return $this->result($file['error']);
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            return $this->result(static::ERR_NOT_UPLOADED_FILE);
        }
        
        // Check if size is valid
        if ($this->maxSize && $this->maxSize < $file['size']) {
            return $this->result(static::ERR_FILE_TOO_LARGE);
        }
        
        if ($this->minSize && $this->minSize > $file['size']) {
            return $this->result(static::ERR_FILE_TOO_SAMLL);
        }
        
        // Check if extension is valid
        $ext = $file['ext'] = pathinfo($file['name'], PATHINFO_EXTENSION);
        if ($this->blackExts && in_array($ext, (array) $this->blackExts)) {
            return $this->result(static::ERR_EXT_NOT_ALLOW);
        }
        
        if ($this->whiteExts && !in_array($ext, (array) $this->whiteExts)) {
            return $this->result(static::ERR_EXT_NOT_IN_ALLOW);
        }
        
        // Custom saved file name
        if ($this->fileName) {
            if (is_callable($this->fileName)) {
                $fileName = $this->fileName();
            } else {
                $fileName = $this->fileName;
            }
        } else {
            $fileName = $file['name'];
        }
        
        // Looks not good, FIXED ME
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0700, true);
        }
        
        $fullFile = $file['file'] = $this->dir . '/' . $fileName;
        if (@!move_uploaded_file($file['tmp_name'], $fullFile)) {
            return $this->result(static::ERR_MOVE_FAIL);
        }

        return $this->result(UPLOAD_ERR_OK, array('file' => $file));
    }

    /**
     * Set name
     * 
     * @param string $name
     * @return \Qwin\Upload
     */
    public function setName($name)
    {
        $this->name = $name ? $name : key($_FILES);

        return $this;
    }
    
    /**
     * Get uploaded file list
     * 
     * @return array
     */
    public function getFiles()
    {
        return $_FILES;
    }
    
    /**
     * Return result
     * 
     * @param int $code
     * @param array $data
     * @return array
     */
    public function result($code, array $data = array())
    {
        return array(
            'code'      => $code,
            'message'   => $this->results[$code],
        ) + $data;
    }
}
