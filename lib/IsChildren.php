<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is validated by the specified V service
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @mixin \ValidateMixin
 */
class IsChildren extends BaseValidator
{
    protected $typeMessage = '%name% must be array or object';

    /**
     * @var V
     */
    protected $v;

    /**
     * @var Validate
     */
    private $selfValidator;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, V $v = null)
    {
        $v && $this->storeOption('v', $v);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages($name = null)
    {
        if ($this->getErrors()) {
            return parent::getMessages($name);
        }
        return $this->selfValidator->getFlatMessages();
    }

    public function getValidData()
    {
        return $this->selfValidator->getValidData();
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!is_array($input) && !is_object($input)) {
            $this->addError('type');
            return false;
        }

        $options = $this->v->getOptions();
        $this->selfValidator = $this->validate(['data' => $input] + $options);
        return $this->selfValidator->isValid();
    }
}
