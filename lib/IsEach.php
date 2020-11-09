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
 * Check if the input is validated by the specified V service
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @mixin \ValidateMixin
 */
class IsEach extends BaseValidator
{
    /**
     * @var V
     */
    protected $v;

    /**
     * @var Validate[]
     */
    private $selfValidators;

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $v = null)
    {
        $v && $this->storeOption('v', $v);

        return $this->isValid($input);
    }

    protected function doValidate($input)
    {
        $result = true;
        $options = $this->v->getOptions();
        $parentName = $this->validator->getNames()[$this->validator->getCurrentField()] ?? '';

        foreach ($input as $i => $row) {
            $newOptions = $options;
            $newOptions['data'] = $row;
            foreach ($newOptions['names'] as $j => $name) {
                // TODO 翻译
                // The 1st product's stock
                $newOptions['names'][$j] = '第 ' . ($i + 1) . ' 个' . $parentName . '的' . $name;
            }
            $validator = wei()->validate($newOptions);
            $this->selfValidators[] = $validator;

            if ($result && !$validator->isValid()) {
                $result = false;
            }
        }

        return $result;
    }

    public function getMessages($name = null)
    {
        $messages = [];
        foreach ($this->selfValidators as $i => $validator) {
            foreach ($validator->getFlatMessages() as $key => $message) {
                $messages[$i . '-' . $key] = $message;
            }
        }
        return $messages;
    }
}
