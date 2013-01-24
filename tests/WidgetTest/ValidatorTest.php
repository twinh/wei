<?php

namespace WidgetTest;

use Widget\Validator;

class ValidatorTest extends TestCase
{
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
            $data, \Widget\Validator\Callback $callback, \Widget\Widget $widget
        ){
            return false;
        }, 'data'));
    }


    public function testValidator()
    {
        $this->assertTrue($this->is('digit', '123'));

        $this->assertFalse($this->is('digit', 'abc'));

        $result = $this->validate(array(
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
                    'range' => array(
                        'min' => 1,
                        'max' => 150
                    )
                ),
            ),
        ))->valid();

        $this->assertTrue($result);
    }

    public function testOptionalField()
    {
        $result = $this->validate(array(
            'data' => array(
                'email' => ''
            ),
            'rules' => array(
                'email' => array(
                    'required' => false,
                    'email' => true,
                )
            ),
        ))->valid();

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
        $this->validate(array(
            'rules' => array(),
        ));
    }

    public function testBreakOne()
    {
        $breakRule = '';

        $this->validate(array(
            'data' => array(
                'email' => 'error-email',
            ),
            'rules' => array(
                'email' => array(
                    'length' => array(
                        'min' => 1,
                        'max' => 3 
                    ), // invalid
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

        $this->validate(array(
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

        $this->validate(array(
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
    
    public function testIsOne()
    {
        $this->assertTrue($this->is(array(
            'email' => true,
            'endsWith' => array(
                'findMe' => 'example.com',
            ),
        ), 'example@example.com'));
    }
    
    public function testNotPrefix()
    {
        $this->assertTrue($this->is('notDigit', 'string'));
        
        $this->assertFalse($this->is('notDigit', '123'));
    }
    
    public function testIsFieldInvalidted()
    {
        /* @var $validator \Widget\Validator\Validator */
        $validator = $this->validate(array(
            'data' => array(
                'age' => 10,
                'email' => 'example@example.com'
            ),
            'rules' => array(
                'age' => array(
                    'range' => array(
                        'min' => 20,
                        'max' => 30,
                    )
                ),
                'email' => array(
                    'email' => true
                ),
            )
        ));
        
        $this->assertTrue($validator->isFieldInvalidted('age'));
        
        $this->assertTrue($validator->isFieldValidated('email'));
        
        $this->assertEquals(10, $validator->getFieldData('age'));
        
        $this->assertEquals(array('required', 'email'), $validator->getValidatedRules('email'));
        
        $this->assertEquals(array('range'), $validator->getInvalidatedRules('age'));

        $this->assertEquals(array('range'), array_keys($validator->getRules('age')));
        
        $this->assertEquals(array(
            'min' => 20,
            'max' => 30
        ), $validator->getRuleParams('age', 'range'));
        
        $this->assertEmpty($validator->getRuleParams('age', 'noThisRule'));
        
        $this->assertEmpty($validator->getRuleParams('noThisField', 'rule'));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCallback()
    {
        $this->validate(array(
            'data' => array(
                'email' => 'example@example.com',
            ),
            'rules' => array(
                'email' => array(
                    'email' => true,
                ),
            ),
            'success' => 'notCallable'
        ));
    }
    
    public function testRuleOperation()
    {
        /* @var $validator \Widget\Validator\Validator */
        $validator = $this->is->createValidator();
        
        $this->assertFalse($validator->hasRule('username', 'email'));
        
        $validator->addRule('username', 'email', true);
        
        $this->assertTrue($validator->hasRule('username', 'email'));
        
        $this->assertTrue($validator->removeRule('username', 'email'));
        
        $this->assertFalse($validator->removeRule('username', 'email'));
    }
    
    public function testData()
    {
        /* @var $validator \Widget\Validator\Validator */
        $validator = $this->is->createValidator();
        
        $this->assertEmpty($validator->getData());
        
        $data = array(
            'username' => 'example@example.com'
        );
        
        $validator->setData($data);
        
        $this->assertEquals($data, $validator->getData());
        
        $validator->setFieldData('username', 'example');
        
        $this->assertEquals('example', $validator->getFieldData('username'));
    }
    
    public function testGetValidateFields()
    {
        /* @var $validator \Widget\Validator\Validator */
        $validator = $this->validate(array(
            'data' => array(
                'email' => 'a@b.com',
                'id' => 'xx'
            ),
            'rules' => array(
                'email' => array(
                    'email' => true,
                ),
                'id' => array(
                    'email' => true
                )
            )
        ));
        
        $this->assertContains('id', $validator->getInvalidatedFields());
        $this->assertNotContains('email', $validator->getInvalidatedFields());
        
        $this->assertContains('email', $validator->getValidateFields());
        $this->assertNotContains('id', $validator->getValidateFields());
    }
    
    public function testMessage()
    {
        /* @var $validator \Widget\Validator\Validator */
        $validator = $this->validate(array(
            'data' => array(
                'username'  => '',
                'email'     => 'b.com',
                'password'  => '',
                'id'        => 'xx'
            ),
            // All is invalid
            'rules' => array(
                'username' => array(
                    'required' => true
                ),
                'email' => array(
                    'required' => true,
                    'email' => true,
                ),
                'password' => array(
                    'required' => true
                ),
                'id' => array(
                    'length' => array(
                       'min' => 10,
                       'max' => 20,
                    ),
                    'email' => true
                ),
            ),
            'messages' => array(
                'username' => 'The username is required',
                'email' => array(
                    'email' => 'The email is invalid'
                ),
                'id' => array(
                    'length' => 'The length must between 10 and 20',
                )
            ),
        ));
        
        $messages = $validator->getInvalidatedMessages();
        /*The invalid messages look like blow 
        $messages = array(
            'email' => array(
                'email' => 'xxx', // user defined
            ),
            'id' => array(
                'length' => 'xxx',
                'email' => 'xxx', // get from rule validator
            )
        );*/
        $this->assertArrayHasKey('username', $messages);
        
        $this->assertArrayHasKey('email', $messages);
        
        $this->assertArrayHasKey('password', $messages);
        
        $this->assertArrayHasKey('id', $messages);
        
        $this->assertArrayHasKey('email', $messages['email']);
        
        $this->assertArrayHasKey('length', $messages['id']);
        
        $this->assertArrayHasKey('email', $messages['id']);
        
        $this->assertArrayNotHasKey('password', $validator->getMessages());
    }
    
    public function testSimpleRule()
    {
        $validator = $this->validate(array(
            'data' => array(
                'useranme' => '',
            ),
            'rules' => array(
                'username' => 'required'
            )
        ));
        
        $this->assertFalse($validator->valid());
        
        $validator2 = $this->validate(array(
            'data' => array(
                'username' => '',
            ),
            'rules' => array(
                'username' => array(
                    'required',
                    'email',
                ),
            )
        ));
        
        $this->assertFalse($validator2->valid());
    }
    
    public function testObjectAsRule()
    {
        $validator = $this->validate(array(
            'data' => array(
                'username' => '',
            ),
            'rules' => array(
                'username' => array(
                    // FIXME inject widget manager
                    new Validator\Email
                )
            )
        ));
        
        $validator2 = $this->validate(array(
            'data' => array(
                'username' => '',
            ),
            'rules' => array(
                // FIXME inject widget manager
                'username' => new Validator\Email
            ),
        ));
        
        $this->assertFalse($validator2->valid());
    }
    
    public function testLanguage()
    {
        $validator = $this->validate(array(
            'data' => array(
                'username' => ''
            ),
            'rules' => array(
                'username' => 'required'
            ),
            'language' => 'zh-CN'
        ));
        
        $this->assertEquals('zh-CN', $validator->getLanguage());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLanguage()
    {
        $this->validate(array(
            'language' => 'no this language'
        ));
    }
}
