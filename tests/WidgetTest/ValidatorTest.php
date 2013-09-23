<?php

namespace WidgetTest;

/**
 * @property \Widget\Is $is
 * @method bool is()
 * @method \Widget\Validate validate($options = array())
 */
class ValidatorTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIsInException()
    {
        $this->isIn('apple', 'not array');
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
                    'between' => array(
                        'min' => 1,
                        'max' => 150
                    )
                ),
            ),
        ))->isValid();

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
        ))->isValid();

        $this->assertTrue($result);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexpectedType()
    {
        $this->is(new \stdClass());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRuleNotDefined()
    {
        $this->is('noThisRule', 'test');
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
                    'length' => array(1, 3), // not valid
                    'email' => true, // valid
                ),
            ),
            'breakRule' => true,
            'ruleInvalid' => function($rule, $field, $validator) use(&$breakRule) {
                $breakRule = $rule;
            }
        ));

        $this->assertEquals('length', $breakRule);
    }

    public function testReturnFalseOnRuleValidCallback()
    {
        $lastRule = '';

        $this->validate(array(
            'data' => array(
                'email' => 'twinhuang@qq.com',
            ),
            'rules' => array(
                'email' => array(
                    'required' => true, // Avoid automatic added
                    'email' => true, // Will not valid
                ),
            ),
            'ruleValid' => function($rule, $field, $validator) use(&$lastRule) {
                $lastRule = $rule;

                // Return false to break the validation flow
                return false;
            }
        ));

        $this->assertEquals('required', $lastRule);
    }

    public function testReturnFalseInvalidCallback()
    {
        $lastField = '';

        $this->validate(array(
            'data' => array(
                'email' => 'twinhuang@qq.com',
                'age' => 5
            ),
            'rules' => array(
                // Will valid
                'email' => array(
                    'email' => true,
                ),
                // Will not valid
                'age' => array(
                    'between' => array(0, 150)
                ),
            ),
            'fieldValid' => function($field, $validator) use(&$lastField) {
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

    public function testIsFieldInvalidated()
    {
        $validator = $this->validate(array(
            'data' => array(
                'age' => 10,
                'email' => 'example@example.com'
            ),
            'rules' => array(
                'age' => array(
                    'between' => array(
                        'min' => 20,
                        'max' => 30,
                    )
                ),
                'email' => array(
                    'email' => true
                ),
            )
        ));

        $this->assertTrue($validator->isFieldInvalid('age'));

        $this->assertTrue($validator->isFieldValid('email'));

        $this->assertEquals(10, $validator->getFieldData('age'));

        $this->assertEquals(array('required', 'email'), $validator->getValidRules('email'));

        $this->assertEquals(array('between'), $validator->getInvalidRules('age'));

        $this->assertEquals(array('between'), array_keys($validator->getFieldRules('age')));

        $this->assertEquals(array(
            'min' => 20,
            'max' => 30
        ), $validator->getRuleParams('age', 'between'));

        $this->assertEmpty($validator->getRuleParams('age', 'noThisRule'));

        $this->assertEmpty($validator->getRuleParams('noThisField', 'rule'));
    }

    public function testRuleOperation()
    {
        $validator = $this->validate();

        $this->assertFalse($validator->hasRule('username', 'email'));

        $validator->addRule('username', 'email', true);

        $this->assertTrue($validator->hasRule('username', 'email'));

        $this->assertTrue($validator->removeRule('username', 'email'));

        $this->assertFalse($validator->removeRule('username', 'email'));
    }

    public function testData()
    {
        $validator = $this->validate();

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

        $this->assertContains('id', $validator->getInvalidFields());
        $this->assertNotContains('email', $validator->getInvalidFields());

        $this->assertContains('email', $validator->getValidFields());
        $this->assertNotContains('id', $validator->getValidFields());
    }

    public function testMessage()
    {
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

        $messages = $validator->getDetailMessages();

        /*The invalid messages look like blow
        $messages = array(
            'email' => array(
                'email' => array(
                    'email' => 'xxx', // user defined
                )
            ),
            'id' => array(
                'length' => array(
                    'length' => 'xxx'
                 ),
                'email' => array(
                   'email' => 'xxx' // get from rule validator
                )
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

        $this->assertFalse($validator->isValid());

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

        $this->assertFalse($validator2->isValid());
    }

    public function testObjectAsRule()
    {
        $validator = $this->validate(array(
            'data' => array(
                'username' => '',
            ),
            'rules' => array(
                'username' => array(
                    $this->is->createRuleValidator('email')
                )
            )
        ));

        $validator2 = $this->validate(array(
            'data' => array(
                'username' => '',
            ),
            'rules' => array(
                'username' => $this->is->createRuleValidator('email')
            ),
        ));

        $this->assertFalse($validator2->isValid());
    }

    public function testSkip()
    {
        $validator = $this->validate(array(
            'data' => array(
                'email' => 'error-email',
                'username' => 'abc'
            ),
            'rules' => array(
                'email' => array(
                    'required' => true, // valid
                    'length' => array(  // not valid
                        'min' => 1,
                        'max' => 3
                    ),
                    'email' => true,    // not valid
                ),
                'username' => array(
                    'required' => true, // valid
                    'length' => array(  // not valid
                        'min' => 5,
                        'max' => 10
                    ),
                    'alnum',            // not valid
                ),
            ),
            'skip' => true
        ));

        $this->assertCount(1, $validator->getInvalidRules('email'));
        $this->assertContains('length', $validator->getInvalidRules('email'));

        $this->assertCount(1, $validator->getInvalidRules('username'));
        $this->assertContains('length', $validator->getInvalidRules('username'));
    }

    public function testStdClassAsData()
    {
        $data = new \stdClass();
        $data->email = 'test@example.com';

        $validator = $this->validate(array(
            'data' => $data,
            'rules' => array(
                'email' => 'email'
            )
        ));

        $this->assertTrue($validator->isValid());
    }

    public function testGetterAsData()
    {
        $user = new \WidgetTest\Fixtures\User;

        $user->setName('test@example.com');

        $validator = $this->validate(array(
            'data' => $user,
            'rules' => array(
                'name' => 'email'
            )
        ));

        $this->assertTrue($validator->isValid());
    }

    public function testGetterSetter()
    {
        $data = new \stdClass();
        $data->email = 'test@example.com';

        $validator = $this->validate();

        $validator->setData($data);
        $validator->setFieldData('age', 18);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexpectedTypeException()
    {
        $validator = $this->validate();

        $validator->setData('string');
    }

    public function testCustomFieldName()
    {
        $validator = $this->validate(array(
            'data' => array(
                'email' => 'error-email',
            ),
            'rules' => array(
                'email' => array(
                    'required' => true, // valid
                    'length' => array(1, 3), // not valid
                    'email' => true,    // not valid
                )
            ),
            'names' => array(
                'email' => 'Your email'
            )
        ));

        $messages = $validator->getSummaryMessages();
        foreach ($messages['email'] as $message) {
            $this->assertContains('Your email', $message);
        }
    }

    public function testJoinedMessage()
    {
        $validator = $this->validate(array(
            'data' => array(
                'email' => 'error-email',
            ),
            'rules' => array(
                'email' => array(
                    'required' => true,         // valid
                    'length' => array(1, 3),    // not valid
                    'email' => true,            // not valid
                    'endsWith' => '@gmail.com'  // not valid
                )
            ),
            'messages' => array(
                'email' => array(
                    'length' => 'error message 1',
                    'email' => 'error message 2',
                    'endsWith' => 'error message 2' // the same message would be combined
                ),
            )
        ));

        $message = $validator->getJoinedMessage('|');
        $this->assertContains('error message 1', $message);
        $this->assertContains('error message 2', $message);
        $this->assertEquals('error message 1|error message 2', $message);
    }

    public function testGetFirstMessage()
    {
        $validator = $this->validate(array(
            'data' => array(),
            'rules' => array(
                'name' => array(
                    'required' => false
                )
            )
        ));
        $this->assertFalse($validator->getFirstMessage());

        $validator = $this->validate(array(
            'data' => array(),
            'rules' => array(
                'name' => 'required',
                'email' => 'required'
            ),
            'messages' => array(
                'name' => 'error message 1',
                'email' => 'error message 2'
            )
        ));
        $firstMessage = $validator->getFirstMessage();
        $this->assertEquals('error message 1', $firstMessage);
    }

    public function testGetRuleValidator()
    {
        $validator = $this->validate(array(
            'data' => array(
                'email' => 'error-email',
            ),
            'rules' => array(
                'email' => array(
                    'required' => true,         // valid
                    'length' => array(1, 3),    // not valid
                    'email' => true,            // not valid
                )
            ),
            'names' => array(
                'email' => 'Your email'
            )
        ));

        $this->assertInstanceOf('\Widget\Validator\Required', $validator->getRuleValidator('email', 'required'));
        $this->assertInstanceOf('\Widget\Validator\Length', $validator->getRuleValidator('email', 'length'));
        $this->assertInstanceOf('\Widget\Validator\Email', $validator->getRuleValidator('email', 'email'));
    }

    public function testGetNames()
    {
        $validator = $this->validate(array(
            'rules' => array(
                'key' => 'required'
            ),
            'names' => array(
                'key' => 'value'
            )
        ));

        $this->assertEquals(array('key' => 'value'), $validator->getNames());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRule()
    {
        $this->validate(array(
            'rules' => array(
                'key' => new \stdClass()
            )
        ));
    }

    public function testSetDetailMessageForValidator()
    {
        $validator = $this->validate(array(
            'rules' => array(
                'username' => array(
                    'email' => true,    // not valid
                ),
            ),
            'messages' => array(
                'username' => array(
                    'email' => array(
                        'format' => 'custom format message'
                    )
                )
            )
        ));

        $this->assertEquals('custom format message', $validator->getRuleValidator('username', 'email')->getOption('formatMessage'));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidArgumentException()
    {
        $email = $this->is->createRuleValidator('email', array(
            'formatMessage' => '%noThisProperty%'
        ));

        $email('not email');

        $email->getMessages();
    }

    public function testGetNotMessages()
    {
        $email = $this->is->createRuleValidator('notEmail', array(
            'negativeMessage' => 'this value must not be an email address'
        ));

        $email('email@example.com');

        $this->assertContains('this value must not be an email address', $email->getMessages());
    }

    public function testSetMessages()
    {
        $email = $this->is->createRuleValidator('email');

        $email->setMessages(array(
            'format' => 'please provide a valid email address'
        ));

        $email('not email');

        $this->assertContains('please provide a valid email address', $email->getMessages());
    }

    public function testGetName()
    {
        $email = $this->is->createRuleValidator('email');

        $email->setName('email');

        $this->assertEquals('email', $email->getName());
    }

    public function testReset()
    {
        $this->assertTrue($this->isEndsWith('abc', 'bc'));

        $this->assertTrue($this->isEndsWith('abc', 'bc', true));

        $this->assertFalse($this->isEndsWith('abc', 'BC', true));

        // Equals to $this->isEndsWith('abc', null);
        // Not equals to $this->isEndsWith('abc', 'BC', true);
        $this->assertTrue($this->isEndsWith('abc'));
    }

    public function testIntAsMessage()
    {
        $validator = $this->validate(array(
            'data' => array(
                'email' => 'twinhuang',
            ),
            'rules' => array(
                'email' => array(
                    'email' => true
                ),
            ),
            'messages' => array(
                'email' => 123,
            )
        ));
        $this->assertEquals(123, $validator->getFirstMessage());
    }

    public function testNullRules()
    {
        $validator = $this->validate(array(
            'rules' => null
        ));
        $this->assertTrue($validator->isValid());

        $validator = $this->validate(array(
            'rules' => null,
            'messages' => null
        ));
        $this->assertTrue($validator->isValid());
    }

    public function testValidateOptionalZeroValue()
    {
        $validator = $this->validate(array(
            'data' => array(
                'email' => 0
            ),
            'rules' => array(
                'email' => array(
                    'required' => false,
                    'email' => true
                )
            )
        ));

        $this->assertFalse($validator->isValid());
        $this->assertEquals(array('email'), $validator->getInvalidRules('email'));
    }

    public function testGetValidatorMessages()
    {
        $result = $this->isLength('abc', 4, 5);
        $this->assertFalse($result);
        $this->assertContains('ABC', current($this->isLength->getMessages('ABC')));
        $this->assertContains('ABC', $this->isLength->getJoinedMessage("\n", 'ABC'));
        $this->assertEquals('This value', $this->isLength->getName());
    }
}
