<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsLuhnTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForLuhn
     * @param mixed $options
     */
    public function testLuhn($options)
    {
        $this->assertTrue($this->isLuhn($options));
    }

    /**
     * @dataProvider providerForNotLuhn
     * @param mixed $options
     */
    public function testNotLuhn($options)
    {
        $this->assertFalse($this->isLuhn($options));
    }

    public static function providerForLuhn()
    {
        return [
            ['100035542'],
            ['100077858'],
            ['100949445'],
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
        ];
    }

    public static function providerForNotLuhn()
    {
        return [
            [''],
            ['100035540'],
            ['100035541'],
            ['100035543'],
            ['100035544'],
            ['100035545'],
            ['100035546'],
            ['100035547'],
            ['100035548'],
            ['100035549'],
        ];
    }
}
