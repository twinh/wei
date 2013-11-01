<?php

namespace WeiTest\Validator;

class CreditCardTest extends TestCase
{
    /**
     * @dataProvider providerForCreditCard
     */
    public function testCreditCard($input, $type = null)
    {
        $this->assertTrue($this->isCreditCard($input, $type));
    }

    /**
     * @dataProvider providerForNotCreditCard
     */
    public function testNotCreditCard($input, $type = null)
    {
        $this->assertFalse($this->isCreditCard($input, $type));
    }

    public function providerForCreditCard()
    {
        // Test data from
        // http://www.easy400.net/js2/regexp/ccnums.html
        // http://www.paypalobjects.com/en_US/vhelp/paypalmanager_help/credit_card_numbers.htm
        // http://names.igopaygo.com/credit_card/paypal
        // http://www.regexmagic.com/manual/xmppatterncreditcard.html
        return array(
            // validate luhn
            array('4111111111111111'), // Visa
            array('5500000000000004'), // MasterCard
            array('340000000000009'),  // American Express
            array('30000000000004'),   // Diner's Club
            array('30000000000004'),   // Carte Blanche
            array('6011000000000004'), // Discover
            array('201400000000009'),  // en Route
            array('3088000000000009'), // JCB
            array('378282246310005'),  // American Express
            array('5610591081018250'), // Australian BankCard
            array('6011111111111117'), // Discover
            array('6011000990139424'), // Discover
            array('3530111333300000'), // JCB
            array('3566002020360505'), // JCB
            array('5555555555554444'), // MasterCard
            // validate type
            array('340000000000009',    'Amex'),
            array('378282246310005',    'Amex'),
            array('30000000000004',     'DinersClub'),
            array('36000000000008',     'DinersClub'),
            array('38000000000006',     'DinersClub'),
            array('6011000000000004',   'Discover'),
            array('6411000000000000',   'Discover'),
            array('6511000000000009',   'Discover'),
            array('213112345678904',    'JCB'),
            array('180012345678905',    'JCB'),
            array('3512345678901236',   'JCB'),
            array('5500000000000004',   'MasterCard'),
            array('6222222222222225',   'UnionPay'),
            array('4123456789011',      'Visa'),
            array('4111111111111111',   'Visa'),
            array('4123456789011',      'UnionPay,Visa'),
            array('4111111111111111',   array('MasterCard', 'Visa')),
            array('4111111111111111',   'All')
        );
    }

    public function providerForNotCreditCard()
    {
        return array(
            array(array()),            // not string
            array('411111111111111'),  // not 16-digit
            array('4111111111111112'), // luhn not valid
            array('3530111333300001'), // luhn not valid
            // invalid card
            array('4111111111111111', 'MasterCard'),    // Visa
            array('5500000000000004', 'Visa'),          // MasterCard
            array('5500000000000004', 'Visa,UnionPay'), // MasterCard
            array('5500000000000004', array('Visa', 'UnionPay')), // MasterCard

            // valid luhn, valid type, invalid length
            array('411111111111116', 'Visa')
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testException()
    {
        $this->isCreditCard->setType(new \stdClass());
    }
}
