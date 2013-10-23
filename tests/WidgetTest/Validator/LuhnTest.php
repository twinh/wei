<?php

namespace WidgetTest\Validator;

class LuhnTest extends TestCase
{
    /**
     * @dataProvider providerForLuhn
     */
    public function testLuhn($options)
    {
        $this->assertTrue($this->isLuhn($options));
    }

    /**
     * @dataProvider providerForNotLuhn
     */
    public function testNotLuhn($options)
    {
        $this->assertFalse($this->isLuhn($options));
    }

    public function providerForLuhn()
    {
        return array(
            array('100035542'),
            array('100077858'),
            array('100949445'),
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
        );
    }

    public function providerForNotLuhn()
    {
        return array(
            array('100035540'),
            array('100035541'),
            array('100035543'),
            array('100035544'),
            array('100035545'),
            array('100035546'),
            array('100035547'),
            array('100035548'),
            array('100035549'),
        );
    }
}