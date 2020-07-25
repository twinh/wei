<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use Wei\Validator\File as FileValidator;
use Wei\Validator\Image;

/**
 * A service that handles the uploaded files
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request The HTTP request wei
 */
class Upload extends Image
{
    /**
     * Seems that the total post data size is too large
     *
     * @link http://php.net/manual/en/ini.core.php#ini.post-max-size
     */
    protected $postSizeMessage = 'No file uploaded or the total file size is too large, allowed maximum size is %postMaxSize%';

    /**
     * The uploaded file array do not contain the key "$this->field", or error code not available
     */
    protected $noFileMessage = 'No file uploaded, please select a file to upload';

    protected $formLimitMessage = '%name% is larger than the MAX_FILE_SIZE value in the HTML form';

    protected $partialMessage = '%name% was partial uploaded, please try again';

    protected $noTmpDirMessage = 'The temporary upload directory is missing';

    protected $cantWriteMessage = 'Cannot write %name% to disk';

    protected $extensionMessage = 'File upload stopped by extension';

    protected $notUploadedFileMessage = 'No file uploaded';

    protected $cantMoveMessage = 'Cannot move uploaded file';

    /**
     * The name for error message
     *
     * @var string
     */
    protected $name = 'file';

    /**
     * The name defined in the file input, if it's not specified, use the first
     * key in upload files array (equals to $_FILES on default)
     *
     * @var string
     */
    protected $field;

    /**
     * The directory to save file, automatic create it if not exist
     *
     * @var string
     */
    protected $dir = 'uploads';

    /**
     * The custom file name (without extension) as upload file name to save
     *
     * @var string
     */
    protected $fileName;

    /**
     * Whether check if the upload file is valid image or not
     *
     * You can specify any one of the following options to enable image detect
     * * maxWidth
     * * maxHeight
     * * minWidth
     * * minHeight
     *
     * @var bool
     */
    protected $isImage = false;

    /**
     * Whether overwrite existing file, if set to false, the uploader will add
     * a number between file name and extension, like file-1.jpg, file-2.jpg
     *
     * @var bool
     */
    protected $overwrite = false;

    /**
     * Whether in unit test mode
     *
     * @var bool
     */
    protected $unitTest = false;

    /**
     * The max size of post data, for $this->postMaxSize
     *
     * @var string
     */
    protected $postMaxSize;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options + [
                'dir' => $this->dir,
            ]);
    }

    /**
     * Upload a file
     *
     * @param string|array $field The name of file input or options of wei
     * @param array $options The options of wei
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @todo split to small methods
     */
    public function __invoke($field = null, $options = [])
    {
        // ($field, $options)
        if (is_string($field)) {
            $this->storeOption('field', $field);
            $options && $this->storeOption($options);
        // ($options)
        } elseif (is_array($field)) {
            $field && $this->storeOption($field);
        }

        // Clean previous status
        parent::reset();

        $uploadedFiles = $this->request->getParameterReference('file');

        // Set default name
        if (!$this->field) {
            $this->field = key($uploadedFiles);
        }

        // Check if has file uploaded or file too large
        if (!isset($uploadedFiles[$this->field])) {
            $post = $this->request->getParameterReference('post');
            if (empty($uploadedFiles) && empty($post)) {
                $error = 'postSize';
                // Prepare postMaxSize variable for $this->postSizeMessage
                $this->postMaxSize = $this->getIniSize('post_max_size');
            } else {
                $error = 'noFile';
            }
            $this->addError($error);
            return false;
        }

        $uploadedFile = $uploadedFiles[$this->field];

        /**
         * @link http://php.net/manual/en/features.file-upload.errors.php
         */
        if (UPLOAD_ERR_OK !== $uploadedFile['error']) {
            switch ($uploadedFile['error']) {
                // The uploaded file exceeds the upload_max_filesize directive in php.ini
                case UPLOAD_ERR_INI_SIZE:
                    $this->sizeString = $this->fromBytes($uploadedFile['size']);
                    $this->maxSizeString = $this->getIniSize('upload_max_filesize');
                    $this->addError('maxSize');
                    break;

                // The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form
                case UPLOAD_ERR_FORM_SIZE:
                    $this->addError('formLimit');
                    break;

                // The uploaded file was only partially uploaded
                // http://stackoverflow.com/questions/2937466/why-might-a-file-only-be-partially-uploaded
                case UPLOAD_ERR_PARTIAL:
                    $this->addError('partial');
                    break;

                // Missing a temporary folder
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->addError('noTmpDir');
                    break;

                // Failed to write file to disk
                case UPLOAD_ERR_CANT_WRITE:
                    $this->addError('cantWrite');
                    break;

                // A PHP extension stopped the file upload
                case UPLOAD_ERR_EXTENSION:
                    $this->addError('extension');
                    break;

                // No file was uploaded
                case UPLOAD_ERR_NO_FILE:
                default:
                    $this->addError('noFile');
            }
            return false;
        }

        if (!$this->isUploadedFile($uploadedFile['tmp_name'])) {
            $this->addError('notUploadedFile');
            return false;
        }

        // Validate file extension, size, mime type by parent class
        if ($this->isImage || $this->maxWidth || $this->maxHeight || $this->minWidth || $this->minHeight) {
            $result = parent::doValidate($uploadedFile);
        } else {
            $result = FileValidator::doValidate($uploadedFile);
        }
        if (false === $result) {
            return false;
        }

        return $this->saveFile($uploadedFile);
    }

    /**
     * Returns the uploaded file path
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns the uploaded file URL for web access
     *
     * @return string
     */
    public function getUrl()
    {
        if (getcwd() === $this->request->getServer('DOCUMENT_ROOT')) {
            return '/' . $this->file;
        } else {
            // eg, file=public/uploads/1.jpg DOCUMENT_ROOT=/var/www/public cwd=/var/www
            // returns /uploads/1.jpg
            return '/' . substr($this->file, strlen($this->request->getServer('DOCUMENT_ROOT')) - strlen(getcwd()));
        }
    }

    /**
     * Set upload directory
     *
     * @param string $dir
     * @return $this
     */
    public function setDir($dir)
    {
        $dir = rtrim($dir, '/');
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }
        $this->dir = $dir;

        return $this;
    }

    /**
     * Returns upload directory
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Save uploaded file to upload directory
     *
     * @param array $uploadedFile
     * @return bool
     */
    protected function saveFile($uploadedFile)
    {
        $ext = $this->getExt();
        $fullExt = $ext ? '.' . $ext : '';

        if ($this->fileName) {
            $fileName = $this->fileName;
        } else {
            $fileName = substr($uploadedFile['name'], 0, strlen($uploadedFile['name']) - strlen($fullExt));
        }

        $this->file = $this->dir . '/' . $fileName . $fullExt;
        if (!$this->overwrite) {
            $index = 1;
            while (is_file($this->file)) {
                $this->file = $this->dir . '/' . $fileName . '-' . $index . $fullExt;
                ++$index;
            }
        }

        if (!$this->moveUploadedFile($uploadedFile['tmp_name'], $this->file)) {
            $this->addError('cantMove');
            return false;
        }

        return true;
    }

    /**
     * Returns a human readable file size (e.g. 1.2MB, 10KB), which recive from
     * the ini configuration
     *
     * @param string $name The name of ini configuration
     * @return string
     */
    protected function getIniSize($name)
    {
        $size = ini_get($name);
        return is_numeric($size) ? $this->fromBytes($size) : $size;
    }

    /**
     * Check if the file was uploaded via HTTP POST, if $this->unitTest is
     * enable, it will always return true
     *
     * @param string $file
     * @return bool
     */
    protected function isUploadedFile($file)
    {
        return $this->unitTest ? is_file($file) : is_uploaded_file($file);
    }

    /**
     * Moves an uploaded file to a new location, if $this->unitTest is enable,
     * it will use `copy` function instead
     *
     * @param string $from The uploaded file name
     * @param string $to the destination of the moved file
     * @return bool
     */
    protected function moveUploadedFile($from, $to)
    {
        return $this->unitTest ? copy($from, $to) : @move_uploaded_file($from, $to);
    }
}
