<?php

namespace WidgetTest\Validator;

class CreditCardTest extends TestCase
{
    /**
     * @dataProvider providerForCreditCard
     */
    public function testCreditCard($input)
    {
        $this->assertTrue($this->isCreditCard($input));
    }

    /**
     * @dataProvider providerForNotCreditCard
     */
    public function testNotCreditCard($input)
    {
        $this->assertFalse($this->isCreditCard($input));
    }

    public function providerForCreditCard()
    {
        // Test data from
        // http://www.easy400.net/js2/regexp/ccnums.html
        // http://www.paypalobjects.com/en_US/vhelp/paypalmanager_help/credit_card_numbers.htm
        // http://names.igopaygo.com/credit_card/paypal
        return array(
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
        );
    }

    public function providerForNotCreditCard()
    {
        return array(
            array(array()),            // not string
            array('411111111111111'),  // not 16-digit
            array('4111111111111112'), // luhn not valid
            array('3530111333300001'), // luhn not valid
        );
    }
}
