<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input password is secure enough
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsPassword extends BaseValidator
{
    public const VALID_TYPE = 'string';

    protected $lengthTooShortMessage = '%name% must have a length greater than %minLength%';

    protected $lengthTooLongMessage = '%name% must have a length lower than %maxLength%';

    protected $missingCharTypeMessage = '%name% must contains %missingType%';

    protected $missingCharMessage = '%name% must contains %missingCount% of these characters : %missingType%';

    /**
     * The name display in error message
     *
     * @var string
     */
    protected $name = 'Password';

    /**
     * The names of character type
     *
     * @var array
     */
    protected $typeNames = [
        'digit' => 'digits (0-9)',
        'letter' => 'letters (a-z)',
        'lower' => 'lowercase letters (a-z)',
        'upper' => 'uppercase letters (A-Z)',
        'nonAlnum' => 'non-alphanumeric (For example: !, @, or #) characters',
    ];

    /**
     * @var int
     */
    protected $minLength;

    /**
     * @var int
     */
    protected $maxLength;

    /**
     * @var bool
     */
    protected $needDigit = false;

    /**
     * @var bool
     */
    protected $needLetter = false;

    /**
     * @var bool
     */
    protected $needNonAlnum = false;

    /**
     * @var int
     */
    protected $atLeastPresent = 0;

    /**
     * @var array
     */
    protected $regexMap = [
        'digit' => '0-9',
        'letter' => 'a-zA-Z',
        'lower' => 'a-z',
        'upper' => 'A-Z',
        'nonAlnum' => '^0-9a-zA-Z',
    ];

    /**
     * The parameter for $missingCharMessage
     *
     * @var int
     */
    protected $missingCount;

    /**
     * The parameter for $missingCharTypeMessage and $missingCharMessage
     *
     * @var string
     */
    protected $missingType;

    /**
     * The temp variable for message parameter
     *
     * @var array
     */
    protected $missingTypes = [];

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, array $options = [])
    {
        $options && $this->storeOption($options);
        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages($name = null)
    {
        if ($this->missingTypes) {
            $this->loadTranslationMessages();
            $types = [];
            foreach ($this->missingTypes as $type) {
                $types[] = $this->t($type);
            }
            $this->missingType = implode(', ', $types);
        }
        return parent::getMessages($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        $input = $this->toString($input);
        if (null === $input) {
            $this->addError('notString');
            return false;
        }

        $length = strlen($input);

        if ($this->minLength && $length < $this->minLength) {
            $this->addError('lengthTooShort');
            return false;
        }

        if ($this->maxLength && $length > $this->maxLength) {
            $this->addError('lengthTooLong');
            return false;
        }

        // Find out what kind of characters are missing
        $missing = [];
        foreach ($this->regexMap as $type => $regex) {
            if (!preg_match('/[' . $regex . ']/', $input)) {
                $missing[$type] = true;
            }
        }

        $needTypes = [];
        foreach (['digit', 'letter', 'nonAlnum'] as $type) {
            $propertyName = 'need' . ucfirst($type);
            if ($this->{$propertyName} && isset($missing[$type])) {
                $needTypes[] = $type;
            }
        }

        if (count($needTypes)) {
            $this->missingTypes = array_intersect_key($this->typeNames, array_flip($needTypes));
            $this->addError('missingCharType');
            return false;
        }

        if ($this->atLeastPresent) {
            unset($missing['letter']);
            // 'digit', 'lower', 'upper', 'nonAlnum'
            $total = 4;
            $remains = count($missing);
            $needPresent = $this->atLeastPresent - ($total - $remains);

            if ($needPresent > 0) {
                $this->missingTypes = array_intersect_key($this->typeNames, $missing);
                if (count($missing) == $needPresent) {
                    $this->addError('missingCharType');
                } else {
                    $this->missingCount = $needPresent;
                    $this->addError('missingChar');
                }
                return false;
            }
        }

        return true;
    }
}
