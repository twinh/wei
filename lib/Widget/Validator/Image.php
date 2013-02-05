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
class Image extends AbstractRule
{
    protected $message = 'This value must be a valid image';
    
    protected $notFoundMessage = 'This image is not found or not readable';
    
    protected $notDetectedMessage = 'This file is not a valid image or the size of the image could not be detected';

    protected $widthTooBigMessage = 'This image width is too big ({{ maxWidth }}px), allowed maximum width is {{ width }}px';
    
    protected $widthTooSmallMessage = 'This image width is too small ({{ minWidth }}), expected minimum width is {{ width }}px';
    
    protected $heightTooBigMessage = 'This image height is too big ({{ maxHeight }}px), allowed maximum height is {{ height }}px';
    
    protected $heightTooSamllMessage = 'This image height is too small ({{ minHeight }}), expected minimum height is {{ height }}px';
    
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
    
    public function __invoke($file, $options = array())
    {
        $options && $this->option($options);
        
        if (false === stream_resolve_include_path($file)) {
            $this->addError('notFound');
            return false;
        }
        
        $size = @getimagesize($file);
        if (false === $size) {
            $this->addError('notDetected');
            return false;
        }
        
        $this->width = $size[0];
        $this->height = $size[1];
        
        if ($this->maxWidth && $this->maxWidth < $this->width) {
            $this->addError('widthTooBig', array(
                'maxWidth' => $this->maxWidth,
                'width' => $this->width
            ));
        }
        
        if ($this->minWidth && $this->minWidth > $this->width) {
            $this->addError('widthTooSmall', array(
                'minWidth' => $this->minWidth,
                'width' => $this->width
            ));
        }
        
        if ($this->maxHeight && $this->maxHeight < $this->height) {
            $this->addError('heightTooBig', array(
                'maxHeight' => $this->maxHeight,
                'height' => $this->height
            ));
        }
        
        if ($this->minHeight && $this->minHeight > $this->height) {
            $this->addError('heightTooSmall', array(
                'minHeight' => $this->minHeight,
                'height' => $this->height
            ));
        }
        
        return !$this->errors;
    }
}