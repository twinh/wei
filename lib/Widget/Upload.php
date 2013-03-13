<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Validator\Image;

/**
 * Upload
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Post $post The post widget
 * @todo        Use other various instead of global various $_FILES
 * @todo        Add service widget and extend it
 * @todo        language
 * @todo        display size in result message
 */
class Upload extends Image
{
    /**
     * Seems that the total file size is larger than the max size of post data
     *
     * @link http://php.net/manual/en/ini.core.php#ini.post-max-size
     */
    protected $postSizeMessage = 'Seems that the total file size is larger than the max size of allowed post data, please check the size of your file';

    /**
     * $_FILES do not contain the key "$this->name"
     * 
     * @var string
     */
    protected $noFileMessage = 'No file uploaded';
    
    /**
     * File not valid for function is_uploaded_file
     */
    const ERR_NOT_UPLOADED_FILE = 21;
    
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
        27                      => 'Can not move uploaded file',
    );

    /**
     * Upload a file
     *
     * @param array $options
     * @return array
     */
    public function __invoke(array $options = array())
    {
        $this->setOption($options);

        $files = $this->getFiles();

        // Set default name
        if (!$this->name) {
            $this->setName(key($files));
        }

        // TODO detail description for this situation
        // Check if has file uploaded or file too large
        if (!isset($files[$this->name])) {
            if (empty($files) && !$this->post->toArray()) {
                $error = 'postSize';
            } else {
                $error = 'noFile';
            }
            $this->addError($error);
            return false;
        }

        $file = $files[$this->name];

        // Handle UPLOAD_ERR_* error
        if (0 !== $file['error']) {
            return $this->result($file['error']);
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            return $this->result(static::ERR_NOT_UPLOADED_FILE);
        }

        // Custom saved file name
        if ($this->fileName) {
            $fileName = $this->fileName;
        } else {
            $fileName = $file['name'];
        }

        // Looks not good, FIXED ME
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0700, true);
        }

        // TODO beforeSave & afterSave event
        $fullFile = $file['file'] = $this->dir . '/' . $fileName;
        if (@!move_uploaded_file($file['tmp_name'], $fullFile)) {
            return $this->result(static::ERR_MOVE_FAIL);
        }

        return $this->result(UPLOAD_ERR_OK, array('file' => $file));
    }

    /**
     * Set the upload filed name
     *
     * @param string $name
     * @return \Widget\Upload
     */
    public function setName($name)
    {
        $this->name = $name;

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
