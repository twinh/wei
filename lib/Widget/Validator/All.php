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
 * @property \Widget\Is $is The validator manager
 */
class All extends AbstractValidator
{
    protected $notArrayMessage = '%name% must be of type array';
    
    protected $itemName = '%name%\'s %index% item';
    
    public function __invoke($input, array $rules)
    {
        if (!is_array($input) && !$input instanceof \Traversable) {
            $this->addError('notArray');
            return false;
        }

        $index = 1;
        $validator = null;
        foreach ($input as $item) {
            foreach ($rules as $rule => $options) {
                if (!$this->is->validateOne($rule, $item, $options, $validator)) {
                    foreach ($validator->getErrors() as $name => $error) {
                        $this->addError($rule . '.' . $name . '.' . $index, array(
                            'name' => $this->itemName,
                            'index' => $index
                        ) + $error['parameters'], $error['message']);
                    }
                }
            }
            $index++;
        }
        
        return !$this->errors;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        $this->loadTranslationMessages();
        
        foreach ($this->errors as &$error) {
            if (isset($error['parameters']['name'])) {
                $error['parameters']['name'] = $this->t($error['parameters']['name']);
                $error['parameters']['name'] = $this->t($error['parameters']['name'], array(
                    '%name%' => $this->t($this->name),
                    '%index%' => $error['parameters']['index'],
                ));
            }
        }

        return parent::getMessages();
    }
}
