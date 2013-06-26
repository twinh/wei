<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if all of the element in the input is valid by all specified rules
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Is $is The validator manager
 */
class All extends AbstractValidator
{
    protected $notArrayMessage = '%name% must be of type array';
    
    /**
     * This message is just a placeholder, would not display to user
     * 
     * @var string
     */
    protected $invalidMessage = 'Some of the items is not valid';
    
    protected $itemName = '%name%\'s %index% item';
    
    protected $rules = array();
    
    /**
     * The invalid validators
     * 
     * @var array
     */
    protected $validators = array();
    
    /**
     * Check if all of the element in the input is valid by all specified rules
     * 
     * @param array|\Traversable $input The input to be validated
     * @param array $rules The validation rules
     * @return bool
     */
    public function __invoke($input, array $rules = array())
    {
        $rules && $this->storeOption('rules', $rules);
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!is_array($input) && !$input instanceof \Traversable) {
            $this->addError('notArray');
            return false;
        }

        $index = 1;
        $validator = null;
        foreach ($input as $item) {
            foreach ($this->rules as $rule => $options) {
                if (!$this->is->validateOne($rule, $item, $options, $validator)) {
                    $this->validators[$index][$rule] = $validator;
                }
            }
            $index++;
        }
        
        // Adds the placeholder message
        if (count($this->validators)) {
            $this->addError('invalid');
            return false;
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        $this->loadTranslationMessages();
        $translator = $this->t;
        
        // Firstly, translates the item name (%name%'s %index% item)
        // Secondly, translates "%name%" in the item name
        $name = $translator($translator($this->itemName), array(
            '%name%' => $translator($this->name)
        ));
        
        $messages = array();
        foreach ($this->validators as $index => $validators) {
            foreach ($validators as $rule => $validator) {
                // Lastly, translates "index" in the item name
                $validator->setName($translator($name, array(
                    '%index%' => $index
                )));
                foreach ($validator->getMessages() as $option => $message) {
                    $messages[$rule . '.' . $option . '.' . $index] = $message;
                }
            }
        }
        return $messages;
    }
}
