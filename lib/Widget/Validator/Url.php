<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Url extends AbstractValidator
{
    protected $invalidMessage = '%name% must be valid URL';
    
    protected $notMessage = '%name% must not be URL';
    
    /**
     * Requires the URL to contain a path part (like http://www.example.com/path/part)
     * 
     * @var bool
     */
    protected $path = false;
    
    /**
     * Requires URL to have a query string (like http://www.example.com/?query=string)
     * 
     * @var bool
     */
    protected $query = false;
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        $flag = 0;
        if ($this->path) {
            $flag = $flag | FILTER_FLAG_PATH_REQUIRED;
        }
        if ($this->query) {
            $flag = $flag | FILTER_FLAG_QUERY_REQUIRED;
        }

        if (!filter_var($input, FILTER_VALIDATE_URL, $flag)) {
            $this->addError('invalid');
            return false;
        }
        
        return true;
    }
}
