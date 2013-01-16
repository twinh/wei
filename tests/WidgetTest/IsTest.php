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
    
    public function testIsPostCode()
    {
        $this->assertTrue($this->isPostcode('123456'));
        
        $this->assertFalse($this->isPostcode('1234567'));
        
        $this->assertFalse($this->isPostcode('0234567'));
    }
    
    public function testIsFile()
    {
        $this->assertFalse(false, $this->isFile(array()), 'Not File path');

        $this->assertEquals($this->isFile(__FILE__), __FILE__, 'File found');

        $this->assertFalse($this->isFile('.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (is_file($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->isFile($file), 'File in include path found');
                break;
            }
        }
    }
    public function testIsDir()
    {
        $this->assertEquals(false, $this->isDir(array()), 'Not File path');

        $this->assertEquals($this->isDir(__DIR__), __DIR__, 'File found');

        $this->assertFalse($this->isDir('.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->isDir($file), 'File in include path found');
                break;
            }
        }
    }
    
    public function testIsExists()
    {
        $this->assertEquals(false, $this->isExists(array()), 'Not File path');

        $this->assertEquals($this->isExists(__FILE__), __FILE__, 'File found');

        $this->assertFalse($this->isExists('.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (file_exists($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->isExists($file), 'File in include path found');
                break;
            }
        }
    }
    
    public function testIsDate()
    {
        $this->assertTrue($this->isDate('2013-01-13'));
        
        $this->assertTrue($this->isDate('1000-01-01'));
        
        $this->assertTrue($this->isDate('3000-01-01'));
        
        $this->assertTrue($this->isDate('2012-02-29'));
        
        $this->assertFalse($this->isDate('2013-02-29'));
        
        $this->assertFalse($this->isDate('2013-01-32'));
        
        $this->assertFalse($this->isDate('2013-00-00'));
        
        $this->assertFalse($this->isDate('2012'));
    }
    
    public function testIsDateTime()
    {
        $this->assertTrue($this->isDateTime('1000-01-01 00:00:00'));
        
        $this->assertTrue($this->isDateTime('3000-01-01 00:00:50'));
        
        $this->assertTrue($this->isDateTime('2012-02-29 23:59:59'));
        
        $this->assertFalse($this->isDateTime('2013-02-29 24:00:00'));
        
        $this->assertFalse($this->isDateTime('2013-01-32 23:60:00'));
        
        $this->assertFalse($this->isDateTime('2013-00-00 23:59:61'));
        
        $this->assertFalse($this->isDateTime('2012 61:00'));
    }
    
    public function testIsRange()
    {
        $this->assertTrue($this->isRange(20, 10, 30));
        
        $this->assertTrue($this->isRange('2013-01-13', '2013-01-01', '2013-01-31'));
        
        $this->assertTrue($this->isRange(1.5, 0.9, 3.2));
        
        $this->assertFalse($this->isRange(20, 30, 40));
    }
    
    public function testIsIn()
    {
        $this->assertTrue($this->isIn('apple', array('apple', 'pear')));
        
        $this->assertTrue($this->isIn('apple', new \ArrayObject(array('apple', 'pear'))));
        
        $this->assertTrue($this->isIn('', array(null)));
        
        $this->assertFalse($this->isIn('', array(null), true));
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIsInException()
    {
        $this->isIn('apple', 'not array');
    }
    
    public function testIsEndsWith()
    {
        $this->assertTrue($this->isEndsWith('abc', 'c'));
        
        $this->assertFalse($this->isEndsWith('abc', ''));
        
        $this->assertFalse($this->isEndsWith('abc', 'b'));
        
        $this->assertTrue($this->isEndsWith('ABC', 'c'));
        
        $this->assertFalse($this->isEndsWith('ABC', 'c', true));
    }
    
    public function testIsStartsWith()
    {
        $this->assertTrue($this->isStartsWith('abc', 'a'));
        
        $this->assertFalse($this->isStartsWith('abc', ''));
        
        $this->assertFalse($this->isStartsWith('abc', 'b'));
        
        $this->assertTrue($this->isStartsWith('ABC', 'A'));
        
        $this->assertFalse($this->isStartsWith('ABC', 'a', true));
    }
    
    public function testClosureAsParameter()
    {
        $this->assertTrue($this->is(function($data){
            return 'abc' === $data;
        }, 'abc'));
        
        $this->assertFalse($this->is(function(
            $data, \Widget\Is\Rule\Callback $callback, \Widget\Widget $widget
        ){
            return false;
        }, 'data'));
    }
        
    
    public function testValidator()
    {
        $this->assertTrue($this->is('digit', '123'));
        
        $this->assertFalse($this->is('digit', 'abc'));
        
        $result = $this->is(array(
            'data' => array(
                'email' => 'twinhuang@qq.com',
                'age' => '5',
            ),
            'rules' => array(
                'email' => array(
                    'email' => true
                ),
                'age' => array(
                    'digit' => true,
                    'range' => array(1, 150)
                ),
            ),
        ));
        
        $this->assertTrue($result);
    }
    
    public function testOptionalField()
    {
        $result = $this->is(array(
            'data' => array(
                'email' => ''
            ),
            'rules' => array(
                'email' => array(
                    'required' => false,
                    'email' => true,
                )
            ),
        ));
        
        $this->assertTrue($result);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $this->is(new \stdClass());
    }
    
    /**
     * @expectedException \Widget\Exception
     */
    public function testRuleNotDefined()
    {
        $this->is('noThisRule', 'test');
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEmptyRuleException()
    {
        $this->is(array(
            'rules' => array(),
        ));
    }
    
    public function testBreakOne()
    {
        $breakRule = '';
        
        $this->is(array(
            'data' => array(
                'email' => 'error-email',
            ),
            'rules' => array(
                'email' => array(
                    'length' => array(1, 3), // invalid
                    'email' => true, // valid
                ),
            ),
            'breakOne' => true,
            'invalidatedOne' => function($field, $rule, $validator) use(&$breakRule) {
                $breakRule = $rule;
            }
        ));
        
        $this->assertEquals('length', $breakRule);
    }
    
    public function testReturnFalseInValidatedOneCallback()
    {
        $lastRule = '';
        
        $this->is(array(
            'data' => array(
                'email' => 'twinhuang@qq.com',
            ),
            'rules' => array(
                'email' => array(
                    'required' => true, //Aavoid automatic added 
                    'email' => true, // Will not validate
                ),
            ),
            'validatedOne' => function($field, $rule, $validator) use(&$lastRule) {
                $lastRule = $rule;
            
                // Return false to break the validation flow
                return false;
            }
        ));
        
        $this->assertEquals('required', $lastRule);
    }
    
    public function testReturnFalseInValidatedCallback()
    {
        $lastField = '';
        
        $this->is(array(
            'data' => array(
                'email' => 'twinhuang@qq.com',
                'age' => 5 
            ),
            'rules' => array(
                // Will validate
                'email' => array(
                    'email' => true,
                ),
                // Will not validate
                'age' => array(
                    'range' => array(0, 150)
                ),
            ),
            'validated' => function($field, $validator) use(&$lastField) {
                $lastField = $field;
            
                // Return false to break the validation flow
                return false;
            }
        ));
        
        $this->assertEquals('email', $lastField);
    }
}