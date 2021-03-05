<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid file
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsFile extends BaseValidator
{
    protected $notFoundMessage = '%name% is not found or not readable';

    protected $maxSizeMessage = '%name% is too large(%sizeString%), allowed maximum size is %maxSizeString%';

    protected $minSizeMessage = '%name% is too small(%sizeString%), expected minimum size is %minSizeString%';

    protected $extsMessage = '%name% extension(%ext%) is not allowed, allowed extension: %exts%';

    protected $excludeExtsMessage = '%name% extension(%ext%) is not allowed, not allowed extension: %excludeExts%';

    protected $mimeTypeNotDetectedMessage = '%name% mime type could not be detected';

    protected $mimeTypesMessage = '%name% mime type "%mimeType%" is not allowed';

    protected $excludeMimeTypesMessage = '%name% mime type "%mimeType%" is not allowed';

    protected $negativeMessage = '%name% must be a non-existing file';

    /**
     * The absolute file path, or false when file not found or not readable
     *
     * @var string|false
     */
    protected $file;

    /**
     * The origin name of uploaded file, if the input is not uploaded file
     * array, the origin name is equals to $this->file
     *
     * @var string
     */
    protected $originFile;

    /**
     * The detected byte size of file
     *
     * @var int
     */
    protected $size;

    /**
     * The formatted file size, e.g. 1.2MB, 10KB
     *
     * @var string
     */
    protected $sizeString;

    /**
     * The maximum file size limit
     *
     * @var int
     */
    protected $maxSize = 0;

    /**
     * The formatted maximum file size, e.g. 1.2MB, 10KB
     *
     * @var string
     */
    protected $maxSizeString;

    /**
     * The minimum file size limit
     *
     * @var int
     */
    protected $minSize = 0;

    /**
     * The formatted minimum file size, e.g. 1.2MB, 10KB
     *
     * @var string
     */
    protected $minSizeString;

    /**
     * The detected file extension
     *
     * @var string
     */
    protected $ext;

    /**
     * The allowed file extensions
     *
     * @var array
     */
    protected $exts = [];

    /**
     * The excluding file extensions
     *
     * @var array
     */
    protected $excludeExts = [];

    /**
     * The detected file mime type
     *
     * @var string
     */
    protected $mimeType;

    /**
     * The allowed file mime types
     *
     * @var array
     */
    protected $mimeTypes = [];

    /**
     * The excluding file mime types
     *
     * @var array
     */
    protected $excludeMimeTypes = [];

    /**
     * The file size unit
     *
     * @var string
     */
    protected $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    /**
     * The magic database file to detect file mime type
     *
     * @link http://www.php.net/manual/en/function.finfo-open.php
     * @var string|null
     */
    protected $magicFile;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $options = [])
    {
        $options && $this->storeOption($options);

        return $this->isValid($input);
    }

    /**
     * Checks if a mime type exists in a mime type array
     *
     * @param string $findMe    The mime type to be searched
     * @param array $mimeTypes  The mime type array, allow element likes
     *                          "image/*" to match all image mime type, such as
     *                          "image/gif", "image/jpeg", etc
     * @return bool
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
     * @return IsFile
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
     * @return IsFile
     */
    public function setExcludeExts($exts)
    {
        $this->excludeExts = $this->convertToArray($exts);
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

        $exponent = array_search($unit, $this->units, true);
        return (int) ($value * 1024 ** $exponent);
    }

    /**
     * Formats bytes to human readable file size (e.g. 1.2MB, 10KB)
     *
     * @param int $bytes
     * @return string
     */
    public function fromBytes($bytes)
    {
        for ($i = 0; $bytes >= 1024 && $i < 8; ++$i) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . $this->units[$i];
    }

    /**
     * Set the file mime types
     *
     * @param string|array $mimeTypes
     * @return IsFile
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
     * @return IsFile
     */
    public function setExcludeMimeTypes($excludeMimeTypes)
    {
        $this->excludeMimeTypes = $this->convertToArray($excludeMimeTypes);
        return $this;
    }

    /**
     * Returns the file mime type on success
     *
     * @return string|false
     * @throws \UnexpectedValueException When failed to open fileinfo database
     */
    public function getMimeType()
    {
        if (!$this->mimeType) {
            $finfo = finfo_open(\FILEINFO_MIME_TYPE, $this->magicFile);
            if (!$finfo) {
                throw new \UnexpectedValueException('Failed to open fileinfo database');
            }
            $this->mimeType = finfo_file($finfo, $this->file);
        }
        return $this->mimeType;
    }

    /**
     * Returns the file extension, if file is not exists, return null instead
     *
     * @return string
     */
    public function getExt()
    {
        if (null === $this->ext && $this->originFile) {
            $file = basename($this->originFile);
            // Use substr instead of pathinfo, because pathinfo may return error value in unicode
            if (false !== $pos = strrpos($file, '.')) {
                $this->ext = strtolower(substr($file, $pos + 1));
            } else {
                $this->ext = '';
            }
        }
        return $this->ext;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @todo split to small methods
     */
    protected function doValidate($input)
    {
        switch (true) {
            case is_string($input):
                $file = $originFile = $input;
                break;

            // File array from $_FILES
            case is_array($input):
                if (!isset($input['tmp_name']) || !isset($input['name'])) {
                    $this->addError('notFound');
                    return false;
                }

                $file = $input['tmp_name'];
                $originFile = $input['name'];
                break;

            case $input instanceof \SplFileInfo:
                $file = $originFile = $input->getPathname();
                break;

            default:
                $this->addError('notString');
                return false;
        }

        $this->originFile = $originFile;
        $this->file = $file = stream_resolve_include_path($file);
        if (!$file || !is_file($file)) {
            $this->addError('notFound');
            return false;
        }

        // Validate file extension
        if ($this->exts || $this->excludeExts) {
            $ext = $this->getExt();
        }

        if ($this->excludeExts && in_array($ext, $this->excludeExts, true)) {
            $this->addError('excludeExts');
        }

        if ($this->exts && !in_array($ext, $this->exts, true)) {
            $this->addError('exts');
        }

        // Validate file size
        if ($this->maxSize || $this->minSize) {
            $this->size = filesize($file);
            $this->sizeString = $this->fromBytes($this->size);
        }

        if ($this->maxSize && $this->maxSize <= $this->size) {
            $this->addError('maxSize');
        }

        if ($this->minSize && $this->minSize > $this->size) {
            $this->addError('minSize');
        }

        // Validate file mime type
        if ($this->mimeTypes || $this->excludeMimeTypes) {
            $mimeType = $this->getMimeType();
            if (!$mimeType) {
                $this->addError('mimeTypeNotDetected');
                return false;
            }
        }

        if ($this->mimeTypes && !$this->inMimeType($mimeType, $this->mimeTypes)) {
            $this->addError('mimeTypes');
        }

        if ($this->excludeMimeTypes && $this->inMimeType($mimeType, $this->excludeMimeTypes)) {
            $this->addError('excludeMimeTypes');
        }

        return !$this->errors;
    }

    /**
     * Set maximum file size
     *
     * @param string|int $maxSize
     * @return IsFile
     */
    protected function setMaxSize($maxSize)
    {
        $this->maxSize = $this->toBytes($maxSize);
        $this->maxSizeString = $this->fromBytes($this->maxSize);
        return $this;
    }

    /**
     * Set the minimum file size
     *
     * @param string|int $minSize
     * @return IsFile
     */
    protected function setMinSize($minSize)
    {
        $this->minSize = $this->toBytes($minSize);
        $this->minSizeString = $this->fromBytes($this->minSize);
        return $this;
    }

    /**
     * Converts parameter to array
     *
     * @param mixed $var
     * @return array
     * @throws \InvalidArgumentException When parameter is not a string or array
     */
    protected function convertToArray($var)
    {
        if (is_string($var)) {
            return explode(',', $var);
        } elseif (is_array($var)) {
            return $var;
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of type string or array, "%s" given',
                is_object($var) ? get_class($var) : gettype($var)
            ));
        }
    }
}
