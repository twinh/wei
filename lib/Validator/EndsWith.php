<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is ends with specified string
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class EndsWith extends BaseValidator
{
    protected $notFoundMessage = '%name% must end with "%findMe%"';

    protected $negativeMessage = '%name% must not end with "%findMe%"';

    protected $findMe;

    protected $case = false;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $findMe = null, $case = null)
    {
        $findMe && $this->storeOption('findMe', $findMe);
        is_bool($case) && $this->storeOption('case', $case);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (is_scalar($this->findMe)) {
            $pos = strlen($input) - strlen($this->findMe);
            $fn = $this->case ? 'strrpos' : 'strripos';
            if ($pos !== $fn($input, (string)$this->findMe)) {
                $this->addError('notFound');
                return false;
            }
        } elseif (is_array($this->findMe)) {
            $pattern = array();
            foreach ($this->findMe as $value) {
                $pattern[] = preg_quote($value, '/');
            }
            $pattern = '/' . implode('|', $pattern) . '$/';
            $this->case || $pattern .= 'i';

            if (!preg_match($pattern, $input)) {
                $this->addError('notFound');
                return false;
            }
        }

        return true;
    }
}
