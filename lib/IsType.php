<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the type of input is equals specified type name
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsType extends BaseValidator
{
    protected $typeMessage = '%name% must be %typeName%';

    protected $negativeMessage = '%name% must not be %typeName%';

    /**
     * The expected type of input
     *
     * @var string
     */
    protected $type;

    /**
     * The translated type name for display
     *
     * @var string
     */
    protected $typeName;

    /**
     * {@inheritdoc}
     *
     * @param string $type The expected type of $input
     */
    public function __invoke($input, $type = null)
    {
        $type && $this->storeOption('type', $type);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages($name = null)
    {
        $this->loadTranslationMessages();
        $this->typeName = $this->t($this->type);
        return parent::getMessages($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (function_exists($fn = 'is_' . $this->type)) {
            $result = $fn($input);
        } elseif (function_exists($fn = 'ctype_' . $this->type)) {
            $result = $fn($input);
        } else {
            $result = $input instanceof $this->type;
        }

        if (!$result) {
            $this->addError('type');
        }

        return $result;
    }
}
