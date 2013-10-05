<?php

namespace Widget\Validator;

class Password extends BaseValidator
{
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

    protected $trans = array(
        'digit' => 'digits (0-9)',
        'letter' => 'letters (a-z)',
        'lower' => 'lowercase letters (a-z)',
        'upper' => 'uppercase letters (A-Z)',
        'nonAlnum' => 'non-alphanumeric (For example: !, @, or #) characters'
    );

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
    protected $regexMap = array(
        'digit' => '0-9',
        'letter' => 'a-zA-Z',
        'lower' => 'a-z',
        'upper' => 'A-Z',
        'nonAlnum' => '^0-9a-zA-Z'
    );

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
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
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
        $missing = array();
        foreach ($this->regexMap as $type => $regex) {
            if (!preg_match('/[' . $regex . ']/', $input)) {
                $missing[$type] = true;
            }
        }

        $needTypes = array();
        foreach (array('digit', 'letter', 'nonAlnum') as $type) {
            $propertyName = 'need' . ucfirst($type);
            if ($this->$propertyName && isset($missing[$type])) {
                $needTypes[] = $type;
            }
        }

        if (count($needTypes)) {
            $txt = array_intersect_key($this->trans, array_flip($needTypes));
            $this->missingType = implode(', ', $txt);
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
                if (count($missing) == 1) {
                    $this->missingType = key($missing);
                    $this->addError('missingCharType');
                } else {
                    $this->missingCount = $needPresent;
                    $txt = array_intersect_key($this->trans, $missing);
                    $this->missingType = implode(', ', $txt);
                    $this->addError('missingChar');
                }
                return false;
            }
        }

        return true;
    }
}