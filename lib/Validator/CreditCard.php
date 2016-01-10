<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is valid credit card number
 *
 * The type of credit card could be American Express, Diners Club, Discover,
 * JCB, MasterCard, China UnionPay or Visa
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class CreditCard extends BaseValidator
{
    protected $invalidMessage = '%name% must be valid credit card number';

    protected $negativeMessage = '%name% must not be valid credit card number';

    /**
     * Allowed credit cards name
     *
     * @var array
     */
    protected $type = array();

    /**
     * The array contains card name, length and validation pattern
     *
     * @var array
     * @link http://en.wikipedia.org/wiki/Credit_card_number
     */
    protected $cards = array(
        'Amex'          => array( // American Express
            'length'        => 15,
            'pattern'       => '34|37'
        ),
        'DinersClub'    => array(
            'length'        => 14,
            'pattern'       => '30|36|38'
        ),
        'Discover' => array(
            'length'        => 16,
            'pattern'       => '6011|64|65'
        ),
        'JCB'           => array(
            'length'        => array(15, 16),
            'pattern'       => '2131|1800|35'
        ),
        'MasterCard'    => array(
            'length'        => 16,
            'pattern'       => '51|52|53|54|55'
        ),
        'UnionPay'      => array(
            'length'        => 16,
            'pattern'       => '62'
        ),
        'Visa'          => array(
            'length'        => array(13, 16),
            'pattern'       => '4'
        )
    );

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $type = null)
    {
        $type && $this->storeOption('type', $type);

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

        if (!$this->validateLuhn($input)) {
            $this->addError('invalid');
            return false;
        }

        if (!$this->validateType($input)) {
            $this->addError('invalid');
            return false;
        }

        return true;
    }

    /**
     * Check if the input is valid luhn number
     *
     * @param string $input
     * @return bool
     * @link https://gist.github.com/troelskn/1287893
     */
    public function validateLuhn($input)
    {
        $checksum = '';
        foreach (str_split(strrev($input)) as $i => $d) {
            $checksum .= ($i %2 !== 0) ? ($d * 2) : $d;
        }
        return array_sum(str_split($checksum)) % 10 === 0;
    }

    public function validateType($input)
    {
        if (!$this->type) {
            return true;
        }
        foreach ($this->type as $type) {
            $card = $this->cards[$type];
            if (!preg_match('/^' . $card['pattern'] . '/', $input)) {
                continue;
            }
            if (!in_array(strlen($input), (array)$card['length'])) {
                continue;
            }
            return true;
        }
        return false;
    }

    /**
     * Set allowed credit card types, could be array, string separated by
     * comma(,) or string "all" means all supported types
     *
     * @param string|array $type
     * @return CreditCard
     * @throws \InvalidArgumentException When parameter is not array or string
     */
    public function setType($type)
    {
        if (is_string($type)) {
            if ('all' == strtolower($type)) {
                $this->type = array_keys($this->cards);
            } else {
                $this->type = explode(',', $type);
            }
        } elseif (is_array($type)) {
            $this->type = $type;
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of type array or string, "%s" given',
                is_object($type) ? get_class($type) : gettype($type)
            ));
        }

        return $this;
    }
}
