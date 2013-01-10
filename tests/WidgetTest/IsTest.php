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
}