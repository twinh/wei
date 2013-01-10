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
}