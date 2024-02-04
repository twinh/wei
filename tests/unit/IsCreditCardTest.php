<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsCreditCardTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForCreditCard
     * @param mixed $input
     * @param mixed|null $type
     */
    public function testCreditCard($input, $type = null)
    {
        $this->assertTrue($this->isCreditCard($input, $type));
    }

    /**
     * @dataProvider providerForNotCreditCard
     * @param mixed $input
     * @param mixed|null $type
     */
    public function testNotCreditCard($input, $type = null)
    {
        $this->assertFalse($this->isCreditCard($input, $type));
    }

    public static function providerForCreditCard()
    {
        // Test data from
        // http://www.easy400.net/js2/regexp/ccnums.html
        // http://www.paypalobjects.com/en_US/vhelp/paypalmanager_help/credit_card_numbers.htm
        // http://names.igopaygo.com/credit_card/paypal
        // http://www.regexmagic.com/manual/xmppatterncreditcard.html
        return [
            // validate luhn
            ['4111111111111111'], // Visa
            ['5500000000000004'], // MasterCard
            ['340000000000009'],  // American Express
            ['30000000000004'],   // Diner's Club
            ['30000000000004'],   // Carte Blanche
            ['6011000000000004'], // Discover
            ['201400000000009'],  // en Route
            ['3088000000000009'], // JCB
            ['378282246310005'],  // American Express
            ['5610591081018250'], // Australian BankCard
            ['6011111111111117'], // Discover
            ['6011000990139424'], // Discover
            ['3530111333300000'], // JCB
            ['3566002020360505'], // JCB
            ['5555555555554444'], // MasterCard
            // validate type
            ['340000000000009',    'Amex'],
            ['378282246310005',    'Amex'],
            ['30000000000004',     'DinersClub'],
            ['36000000000008',     'DinersClub'],
            ['38000000000006',     'DinersClub'],
            ['6011000000000004',   'Discover'],
            ['6411000000000000',   'Discover'],
            ['6511000000000009',   'Discover'],
            ['213112345678904',    'JCB'],
            ['180012345678905',    'JCB'],
            ['3512345678901236',   'JCB'],
            ['5500000000000004',   'MasterCard'],
            ['6222222222222225',   'UnionPay'],
            ['4123456789011',      'Visa'],
            ['4111111111111111',   'Visa'],
            ['4123456789011',      'UnionPay,Visa'],
            ['4111111111111111',   ['MasterCard', 'Visa']],
            ['4111111111111111',   'All'],
        ];
    }

    public static function providerForNotCreditCard()
    {
        return [
            [[]],            // not string
            ['411111111111111'],  // not 16-digit
            ['4111111111111112'], // luhn not valid
            ['3530111333300001'], // luhn not valid
            // invalid card
            ['4111111111111111', 'MasterCard'],    // Visa
            ['5500000000000004', 'Visa'],          // MasterCard
            ['5500000000000004', 'Visa,UnionPay'], // MasterCard
            ['5500000000000004', ['Visa', 'UnionPay']], // MasterCard

            // valid luhn, valid type, invalid length
            ['411111111111116', 'Visa'],
        ];
    }

    public function testException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->isCreditCard->setType(new \stdClass());
    }
}
