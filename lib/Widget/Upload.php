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
 * @property    \Widget\Post $post The post widget
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
    
    protected $partialMessage = 'Partial file uploaded, please try again';
    
    protected $noTmpDirMessage = 'No temporary directory';
    
    protected $cantWriteMessage = 'Can\'t write to disk';
    
    protected $extensionMessage = 'File upload stopped by extension';
    
    protected $notUploadedFileMessage = 'No file uploaded';
    
    protected $cantMoveMessage = 'Can not move uploaded file';
    
    protected $name = 'File';
    
    /**
     * The name defined in the file input, if it's not specified, use the first
     * key in $_FILES
     *
     * @var string
     */
    protected $field;
    
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
     * Upload a file
     *
     * @param array $options
     * @return array
     */
    public function __invoke($options = array(), $options2 = array())
    {
        $options && $this->setOption($options);

        $files = $this->getFiles();

        // Set default name
        if (!$this->field) {
            $this->field = key($files);
        }

        // TODO detail description for this situation
        // Check if has file uploaded or file too large
        if (!isset($files[$this->field])) {
            if (empty($files) && !$this->post->toArray()) {
                $error = 'postSize';
            } else {
                $error = 'noFile';
            }
            $this->addError($error);
            return false;
        }

        $file = $files[$this->field];

        switch ($file['error']) {
            case UPLOAD_ERR_OK :
                break;
            
            // File larger than upload_max_filesize
            case UPLOAD_ERR_INI_SIZE :
                $this->addError('maxSize');
                break;
            
            // File larger than MAX_FILE_SIZE defiend in html form
            case UPLOAD_ERR_FORM_SIZE :
                $this->addError('maxSize');
                break;
            
            case UPLOAD_ERR_PARTIAL :
                $this->addError('partial');
                break;
            
            case UPLOAD_ERR_NO_FILE :
                $this->addError('noFile');
                break;
            
            case UPLOAD_ERR_NO_TMP_DIR :
                $this->addError('noTmpDir');
                break;
            
            case UPLOAD_ERR_CANT_WRITE :
                $this->addError('cantWrite');
                break;
            
            case UPLOAD_ERR_EXTENSION :
                $this->addError('extension');
                break;

            default :
                $this->addError('noFile');
        }
        
        if (!is_uploaded_file($file['tmp_name'])) {
            $this->addError('notUploadedFile');
            return false;
        }
        
        $this->saveFile($file);
        
        return $this;
    }
    
    protected function saveFile($file)
    {
        $fileName = $this->fileName ?: $file['name'];
        
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0700, true);
        }
        
        $this->file = $this->dir . '/' . $fileName;
        if (!@move_uploaded_file($file['tmp_name'], $this->file)) {
            $this->addError('cantMove');
            $this->logger('critical', $this->cantMoveMessage);
            return false;
        }
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
}
