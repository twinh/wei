<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is valid image
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Image extends File
{
    protected $notDetectedMessage = '%name% is not a valid image or the size of the image could not be detected';

    protected $widthTooBigMessage = '%name% width is too big (%width%px), allowed maximum width is %maxWidth%px';

    protected $widthTooSmallMessage = '%name% width is too small (%width%px), expected minimum width is %minWidth%px';

    protected $heightTooBigMessage = '%name% height is too big (%height%px), allowed maximum height is %maxHeight%px';

    protected $heightTooSmallMessage = '%name% height is too small (%height%px), expected minimum height is %minHeight%px';

    protected $maxWidth;

    protected $minWidth;

    protected $maxHeight;

    protected $minHeight;

    /**
     * The detected width of image
     *
     * @var int
     */
    protected $width;

    /**
     * The detected height of image
     *
     * @var int
     */
    protected $height;

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        parent::doValidate($input);
        if ($this->hasError('notString') || $this->hasError('notFound')) {
            return false;
        }

        // Receive the real file path resolved by parent class
        $file = $this->file;

        $size = @getimagesize($file);
        if (false === $size) {
            $this->addError('notDetected');
            return false;
        }

        $this->width = $size[0];
        $this->height = $size[1];

        if ($this->maxWidth && $this->maxWidth < $this->width) {
            $this->addError('widthTooBig');
        }

        if ($this->minWidth && $this->minWidth > $this->width) {
            $this->addError('widthTooSmall');
        }

        if ($this->maxHeight && $this->maxHeight < $this->height) {
            $this->addError('heightTooBig');
        }

        if ($this->minHeight && $this->minHeight > $this->height) {
            $this->addError('heightTooSmall');
        }

        return !$this->errors;
    }
}
