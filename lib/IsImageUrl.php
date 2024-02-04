<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is valid image URL address
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsImageUrl extends BaseValidator
{
    public const VALID_TYPE = 'string';

    protected $invalidMessage = '%name% must be a valid image URL';

    protected $extsMessage = '%name% extension(%ext%) is not allowed, allowed extension: %exts%';

    protected $negativeMessage = '%name% must not be URL';

    /**
     * The allowed file extensions
     *
     * @var array
     */
    protected $exts = [
        'jpg',
        'jpeg',
        'bmp',
        'gif',
        'png',
    ];

    /**
     * @var int
     */
    protected $maxLength = 255;

    /**
     * The detected file extension
     *
     * @var string
     * @internal
     */
    protected $ext = '';

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, ?int $maxLength = null)
    {
        null !== $maxLength && $this->storeOption('maxLength', $maxLength);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!filter_var($input, \FILTER_VALIDATE_URL, \FILTER_FLAG_PATH_REQUIRED)) {
            $this->addError('invalid');
            return false;
        }

        if (null !== $this->maxLength) {
            $result = $this->validateRule($input, 'maxCharLength', $this->maxLength);
            if (!$result) {
                return $result;
            }
        }

        $path = parse_url($input, \PHP_URL_PATH);
        if (!in_array(strtolower($this->getExt($path)), $this->exts, true)) {
            $this->addError('exts');
            return false;
        }

        return true;
    }

    protected function getExt(string $file): string
    {
        if (false !== $pos = strrpos($file, '.')) {
            $this->ext = strtolower(substr($file, $pos + 1));
        } else {
            $this->ext = '';
        }
        return $this->ext;
    }
}
