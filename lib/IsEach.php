<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use Wei\V;
use Wei\Validate;

/**
 * Check if every item in the input is validated by the specified V service
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @mixin \ValidateMixin
 * @mixin \TMixin
 */
class IsEach extends BaseValidator
{
    protected $notArrayMessage = '%name% must be an array';

    /**
     * @var V|callable
     */
    protected $v;

    /**
     * @var Validate[]
     */
    private $selfValidators = [];

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $v = null)
    {
        if (!$this->validator) {
            throw new \LogicException('The "each" validator should not call directly, please use with \Wei\V');
        }

        if (null !== $v && !is_callable($v)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of type Wei\V or callable, "%s" given',
                is_object($v) ? get_class($v) : gettype($v)
            ));
        }

        $v && $this->storeOption('v', $v);

        return $this->isValid($input);
    }

    protected function doValidate($input)
    {
        if (!is_array($input)) {
            $this->addError('notArray');
            return false;
        }

        $result = true;
        $options = $this->getValidatorOptions();

        foreach ($input as $i => $row) {
            $options['data'] = $row;
            $validator = wei()->validate($options);
            $this->selfValidators[] = $validator;
            if ($result && !$validator->isValid()) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessages($name = null)
    {
        $name = $name ?: $this->name;
        $this->loadTranslationMessages();

        $messages = [];
        foreach ($this->selfValidators as $i => $validator) {
            // TODO Better message builder, support [['name' => 'Xxx', 'index' => 2], ['name' => 'Yyy', 'index' => 1]]
            $parentName = $this->t($this->getItemName($i + 1), [
                '%index%' => $i + 1,
                '%name%' => $name,
            ]);
            foreach ($validator->getFlatMessages() as $key => $message) {
                $messages[$i . '-' . $key] = $parentName . $this->updateSubMessage($message);
            }
        }
        return array_merge($messages, parent::getMessages($name));
    }

    /**
     * Returns validator options from V object or callback
     *
     * @return array[]
     */
    protected function getValidatorOptions()
    {
        if ($this->v instanceof V) {
            return $this->v->getOptions();
        }

        $v = V::new();
        call_user_func($this->v, $v);
        return $v->getOptions();
    }

    private function updateSubMessage($message)
    {
        if (strpos($message, 'The ') === 0) {
            return substr($message, 4);
        }
        return $message;
    }

    private function getItemName($index)
    {
        return 'The %index%' . $this->getSuffix($index) . ' %name%\'s ';
    }

    /**
     * @param int $index
     * @return string
     * @todo use ICU message instead
     */
    private function getSuffix($index)
    {
        if (!in_array(($index % 100), [11, 12, 13])) {
            switch ($index % 10) {
                case 1:
                    return 'st';
                case 2:
                    return 'nd';
                case 3:
                    return 'rd';
            }
        }
        return 'th';
    }
}
