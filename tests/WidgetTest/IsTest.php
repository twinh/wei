<?php

namespace WidgetTest;

class IsTest extends TestCase
{
    public function testIsAlnum()
    {
        $this->assertTrue($this->isAlnum('abcedfg'));
        
        $this->assertTrue($this->isAlnum('a2BcD3eFg4'));
        
        $this->assertTrue($this->isAlnum('045fewwefds'));
        
        $this->assertFalse($this->isAlnum('a bcdefg'));
        
        $this->assertFalse($this->isAlnum('-213a bcdefg'));
    }
    
    public function testIsAlpha()
    {
        $this->assertTrue($this->isAlpha('abcedfg'));
        
        $this->assertTrue($this->isAlpha('aBcDeFg'));
        
        $this->assertFalse($this->isAlpha('abcdefg1'));
        
        $this->assertFalse($this->isAlpha('a bcdefg'));
    }
    
    public function testIsDigit()
    {
        $this->assertTrue($this->isDigit('123456'));
        
        $this->assertTrue($this->isDigit('0123456'));
        
        $this->assertFalse($this->isDigit('0.123'));
        
        $this->assertFalse($this->isDigit('1 23456'));
        
        $this->assertFalse($this->isDigit('string'));
    }
    
    public function testIsLength()
    {
        $this->assertTrue($this->isLength('length7', 7), 'Length is 7');

        $this->assertTrue($this->isLength('length7', 0, 10), 'Length between 0 and 10, or said length less than 10');

        $this->assertTrue($this->isLength('length7', 5, 0), 'Length greater than 5');

        $this->assertFalse($this->isLength('length7', 0), 'Length is not 0');

        $this->assertFalse($this->isLength('length7', -2, -1), 'Length should be positive, so always be false');

        $this->assertFalse($this->isLength('length7', 10, 0), 'Length should not greater than 10');
    }
    
    public function testIsMobile()
    {
        $trues = array(
            '13112345678',
            '13612345678',
            '13800138000',
            '15012345678',
            '15812345678',
            '18812345678',
        );
        
        foreach ($trues as $mobile) {
            $this->assertTrue($this->isMobile($mobile));
        }
        
        $this->assertFalse($this->isMobile('12000000000'));

        $this->assertFalse($this->isMobile('88888888'));
    }
    
    public function testIsQQ()
    {
        $this->assertFalse($this->isQQ('1000'), 'Too short');

        $this->assertFalse($this->isQQ('011111'), 'Should not start with zero');

        $this->assertFalse($this->isQQ('011111'), 'Should not start with zero, even digits');

        $this->assertFalse($this->isQQ('134.433'), 'Not digits');

        $this->assertTrue($this->isQQ('1234567'));
    }
    
    public function testIsRegex()
    {
        $this->assertTrue($this->isRegex('This is Widget Framework.', '/widget/i'));
        
        $this->assertFalse($this->isRegex('This is Widget Framework.', '/that/i'));
    }
    
    public function testIsPhone()
    {
        $this->assertTrue($this->isPhone('020-1234567'));
        
        $this->assertTrue($this->isPhone('0768-123456789'));
        
        $this->assertFalse($this->isPhone('012345-1234567890'));
        
        $this->assertFalse($this->isPhone('010-1234567890'));
        
        // Phone number without city code
        $this->assertTrue($this->isPhone('1234567'));
        
        $this->assertTrue($this->isPhone('123456789'));
        
        $this->assertFalse($this->isPhone('123456'));
    }
    
    public function testIsTime()
    {
        $this->assertTrue($this->isTime('00:00:00'));
        
        $this->assertTrue($this->isTime('00:00'));
        
        $this->assertTrue($this->isTime('23:59:59'));
        
        $this->assertFalse($this->isTime('24:00:00'));
        
        $this->assertFalse($this->isTime('23:60:00'));
        
        $this->assertFalse($this->isTime('23:59:61'));
        
        $this->assertFalse($this->isTime('61:00'));
        
        $this->assertFalse($this->isTime('01:01:01:01'));
    }
}