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
 * The widget that handle file upload
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Post $post The post widget
 * @todo        Add service widget and extend it
 */
class Upload extends Image
{
    protected $typeInvalidMessage = '%name% must be array';
    
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
     * The upload files, equal to $_FILES on default 
     * 
     * @var array
     */
    protected $uploadedFiles = array();
    
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
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        if (!isset($options['uploadedFiles'])) {
            $this->uploadedFiles = $_FILES;
        }
    }

    /**
     * Upload a file
     * 
     * @param string|array $field
     * @param array $options
     * @return bool
     */
    public function __invoke($field = null, $options = array())
    {
        // ($field, $options)
        if (is_string($field)) {
            $this->field = $field;
            $options && $this->setOption($options);
        // ($options)
        } elseif (is_array($field)) {
            $field && $this->setOption($field);
        }
        
        $uploadedFiles = $this->getUploadedFiles();
        
        // Set default name
        if (!$this->field) {
            $this->field = key($uploadedFiles);
        }
        
        // TODO detail description for this situation
        // Check if has file uploaded or file too large
        if (!isset($uploadedFiles[$this->field])) {
            if (empty($uploadedFiles) && !$this->post->toArray()) {
                $error = 'postSize';
            } else {
                $error = 'noFile';
            }
            $this->addError($error);
            return false;
        }

        $uploadedFile = $uploadedFiles[$this->field];

        switch ($uploadedFile['error']) {
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
        
        if (!is_uploaded_file($uploadedFile['tmp_name'])) {
            $this->addError('notUploadedFile');
            return false;
        }
        
        if ($this->isImage || $this->maxWidth || $this->maxHeight || $this->minWidth || $this->minHeight) {
            $result = parent::validate($uploadedFile);
        } else {
            $result = File::validate($uploadedFile);
        }
        
        if ($this->hasError('notString') || $this->hasError('notFound')) {
            return false;
        }
        
        return $this->saveFile($uploadedFile);
    }

    protected function saveFile($uploadedFile)
    {
        $fileName = $this->fileName ?: $uploadedFile['name'];
       
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0700, true);
        }
        
        $this->file = $this->dir . '/' . $fileName;
        if (!@move_uploaded_file($uploadedFile['tmp_name'], $this->file)) {
            $this->addError('cantMove');
            $this->logger->critical($this->cantMoveMessage);
            return false;
        }
        
        return true;
    }

    /**
     * Get uploaded file list
     *
     * @return array
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }
}
