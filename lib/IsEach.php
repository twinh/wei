<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

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
     * The key name of the current validating data
     *
     * @var int|string
     */
    protected $curKey;

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

        if (null !== $v && !$v instanceof V && !is_callable($v)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of type Wei\V or callable, "%s" given',
                is_object($v) ? get_class($v) : gettype($v)
            ));
        }

        $v && $this->storeOption('v', $v);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
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

    public function getValidData()
    {
        return array_map(function (Validate $validator) {
            return $validator->getValidData();
        }, $this->selfValidators);
    }

    /**
     * Return the key name of the current validating data
     *
     * @return int|string
     */
    public function getCurKey()
    {
        return $this->curKey;
    }

    protected function doValidate($input)
    {
        if (!is_array($input)) {
            $this->addError('notArray');
            return false;
        }

        $result = true;
        foreach ($input as $key => $data) {
            $this->curKey = $key;
            $options = $this->getValidatorOptions($data);
            $validator = wei()->validate($options);
            $this->selfValidators[$key] = $validator;
            if ($result && !$validator->isValid()) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Returns validator options from V object or callback
     *
     * @param mixed $data
     * @return array[]
     */
    protected function getValidatorOptions($data)
    {
        if ($this->v instanceof V) {
            $this->v->setData($data);
            return $this->v->getOptions();
        }

        $v = V::new()->setData($data);
        call_user_func($this->v, $v, $this);
        return $v->getOptions();
    }

    private function updateSubMessage($message)
    {
        if (0 === strpos($message, 'The ')) {
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
        $suffixes = [
            1 => 'st',
            2 => 'nd',
            3 => 'rd',
        ];

        if (!in_array(($index % 100), [11, 12, 13], true)) {
            return $suffixes[$index % 10] ?? 'th';
        }

        return 'th';
    }
}
