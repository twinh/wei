<?php

namespace WeiTest;

use Wei\IsEmail;
use Wei\IsLength;
use Wei\IsPositiveInteger;
use Wei\IsRequired;
use Wei\Validate;

/**
 * @property \Wei\Is $is
 * @method bool is()
 * @method \Wei\Validate validate($options = array())
 *
 * @internal
 */
final class ValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function testIsInException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->isIn('apple', 'not array');
    }

    public function testValidator()
    {
        $result = $this->validate([
            'data' => [
                'email' => 'twinhuang@qq.com',
                'age' => '5',
            ],
            'rules' => [
                'email' => [
                    'email' => true,
                ],
                'age' => [
                    'digit' => true,
                    'between' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
            ],
        ])->isValid();

        $this->assertTrue($result);
    }

    public function testOptionalField()
    {
        $result = $this->validate([
            'data' => [
                'email' => '',
            ],
            'rules' => [
                'email' => [
                    'required' => false,
                    'email' => true,
                ],
            ],
        ])->isValid();

        $this->assertFalse($result);
    }

    public function testOptionalFieldWithObjectData()
    {
        $result = $this->validate([
            'data' => (object) [
                'email' => '',
            ],
            'rules' => [
                'email' => [
                    'required' => false,
                    'email' => true,
                ],
            ],
        ])->isValid();

        $this->assertFalse($result);
    }

    public function testOptionalFieldWihGetter()
    {
        $user = new \WeiTest\Fixtures\User();
        $user->setName('');

        $result = $this->validate([
            'data' => $user,
            'rules' => [
                'name' => [
                    'required' => false,
                    'email' => true,
                ],
            ],
        ])->isValid();

        $this->assertFalse($result);
    }

    public function testDefaultRequired()
    {
        $validate = $this->validate([
            'defaultRequired' => false,
            'data' => [
            ],
            'rules' => [
                'email' => [
                    'email' => true,
                ],
            ],
        ]);

        $this->assertTrue($validate->isValid());

        /** @var IsRequired $required */
        $required = $validate->getRuleValidator('email', 'required');
        $this->assertFalse($required->getOption('required'));
    }

    public function testDefaultRequiredFail()
    {
        $validate = $this->validate([
            'defaultRequired' => false,
            'data' => [
                'email' => '',
            ],
            'rules' => [
                'email' => [
                    'email' => true,
                ],
            ],
        ]);

        $this->assertFalse($validate->isValid());
    }

    public function testRuleNotDefined()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->validate([
            'data' => [
                'username' => 'test',
            ],
            'rules' => [
                'username' => [
                    'noThisRule' => true,
                ],
            ],
        ]);
    }

    public function testBreakOne()
    {
        $breakRule = '';

        $this->validate([
            'data' => [
                'email' => 'error-email',
            ],
            'rules' => [
                'email' => [
                    'length' => [1, 3], // not valid
                    'email' => true, // valid
                ],
            ],
            'breakRule' => true,
            'ruleInvalid' => function ($rule, $field, $validator) use (&$breakRule) {
                $breakRule = $rule;
            },
        ]);

        $this->assertEquals('length', $breakRule);
    }

    public function testReturnFalseOnRuleValidCallback()
    {
        $lastRule = '';

        $this->validate([
            'data' => [
                'email' => 'twinhuang@qq.com',
            ],
            'rules' => [
                'email' => [
                    'required' => true, // Avoid automatic added
                    'email' => true, // Will not valid
                ],
            ],
            'ruleValid' => function ($rule, $field, $validator) use (&$lastRule) {
                $lastRule = $rule;

                // Return false to break the validation flow
                return false;
            },
        ]);

        $this->assertEquals('required', $lastRule);
    }

    public function testReturnFalseInvalidCallback()
    {
        $lastField = '';

        $this->validate([
            'data' => [
                'email' => 'twinhuang@qq.com',
                'age' => 5,
            ],
            'rules' => [
                // Will valid
                'email' => [
                    'email' => true,
                ],
                // Will not valid
                'age' => [
                    'between' => [0, 150],
                ],
            ],
            'fieldValid' => function ($field, $validator) use (&$lastField) {
                $lastField = $field;

                // Return false to break the validation flow
                return false;
            },
        ]);

        $this->assertEquals('email', $lastField);
    }

    public function testIsFieldInvalidated()
    {
        $validator = $this->validate([
            'data' => [
                'age' => 10,
                'email' => 'example@example.com',
            ],
            'rules' => [
                'age' => [
                    'between' => [
                        'min' => 20,
                        'max' => 30,
                    ],
                ],
                'email' => [
                    'email' => true,
                ],
            ],
        ]);

        $this->assertTrue($validator->isFieldInvalid('age'));

        $this->assertTrue($validator->isFieldValid('email'));

        $this->assertEquals(10, $validator->getFieldData('age'));

        $this->assertEquals(['required', 'email'], $validator->getValidRules('email'));

        $this->assertEquals(['between'], $validator->getInvalidRules('age'));

        $this->assertEquals(['between'], array_keys($validator->getFieldRules('age')));

        $this->assertEquals([
            'min' => 20,
            'max' => 30,
        ], $validator->getRuleParams('age', 'between'));

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

        $data = [
            'username' => 'example@example.com',
        ];

        $validator->setData($data);

        $this->assertEquals($data, $validator->getData());

        $validator->setFieldData('username', 'example');

        $this->assertEquals('example', $validator->getFieldData('username'));
    }

    public function testGetValidateFields()
    {
        $validator = $this->validate([
            'data' => [
                'email' => 'a@b.com',
                'id' => 'xx',
            ],
            'rules' => [
                'email' => [
                    'email' => true,
                ],
                'id' => [
                    'email' => true,
                ],
            ],
        ]);

        $this->assertContains('id', $validator->getInvalidFields());
        $this->assertNotContains('email', $validator->getInvalidFields());

        $this->assertContains('email', $validator->getValidFields());
        $this->assertNotContains('id', $validator->getValidFields());
    }

    public function testMessage()
    {
        $validator = $this->validate([
            'data' => [
                'email' => 'b.com',
                'id' => 'xx',
            ],
            // All is invalid
            'rules' => [
                'username' => [
                    'required' => true,
                ],
                'email' => [
                    'required' => true,
                    'email' => true,
                ],
                'password' => [
                    'required' => true,
                ],
                'id' => [
                    'length' => [
                        'min' => 10,
                        'max' => 20,
                    ],
                    'email' => true,
                ],
            ],
            'messages' => [
                'username' => 'The username is required',
                'email' => [
                    'email' => 'The email is invalid',
                ],
                'id' => [
                    'length' => 'The length must between 10 and 20',
                ],
            ],
        ]);

        $messages = $validator->getDetailMessages();

        /*
            The invalid messages look like blow
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
            );
        */

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
        $validator = $this->validate([
            'data' => [
                'useranme' => '',
            ],
            'rules' => [
                'username' => 'required',
            ],
        ]);

        $this->assertFalse($validator->isValid());

        $validator2 = $this->validate([
            'data' => [
                'username' => '',
            ],
            'rules' => [
                'username' => [
                    'required',
                    'email',
                ],
            ],
        ]);

        $this->assertFalse($validator2->isValid());
    }

    public function testObjectAsRule()
    {
        $validator = $this->validate([
            'data' => [
                'username' => '',
            ],
            'rules' => [
                'username' => $this->validate->createRuleValidator('email'),
            ],
        ]);

        $this->assertFalse($validator->isValid());
    }

    public function testSkip()
    {
        $validator = $this->validate([
            'data' => [
                'email' => 'error-email',
                'username' => 'abc',
            ],
            'rules' => [
                'email' => [
                    'required' => true, // valid
                    'length' => [  // not valid
                        'min' => 1,
                        'max' => 3,
                    ],
                    'email' => true,    // not valid
                ],
                'username' => [
                    'required' => true, // valid
                    'length' => [  // not valid
                        'min' => 5,
                        'max' => 10,
                    ],
                    'alnum',            // not valid
                ],
            ],
            'skip' => true,
        ]);

        $this->assertCount(1, $validator->getInvalidRules('email'));
        $this->assertContains('length', $validator->getInvalidRules('email'));

        $this->assertCount(1, $validator->getInvalidRules('username'));
        $this->assertContains('length', $validator->getInvalidRules('username'));
    }

    public function testStdClassAsData()
    {
        $data = new \stdClass();
        $data->email = 'test@example.com';

        $validator = $this->validate([
            'data' => $data,
            'rules' => [
                'email' => 'email',
            ],
        ]);

        $this->assertTrue($validator->isValid());
    }

    public function testGetterAsData()
    {
        $user = new \WeiTest\Fixtures\User();

        $user->setName('test@example.com');

        $validator = $this->validate([
            'data' => $user,
            'rules' => [
                'name' => 'email',
            ],
        ]);

        $this->assertTrue($validator->isValid());
    }

    public function testGetterSetter()
    {
        $data = new \stdClass();
        $data->email = 'test@example.com';

        $validator = $this->validate();

        $validator->setData($data);
        $this->assertSame('test@example.com', $validator->getFieldData('email'));

        $validator->setFieldData('age', 18);
        $this->assertSame(18, $validator->getFieldData('age'));
    }

    public function testUnexpectedTypeException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $validator = $this->validate();

        $validator->setData('string');
    }

    public function testCustomFieldName()
    {
        $validator = $this->validate([
            'data' => [
                'email' => 'error-email',
            ],
            'rules' => [
                'email' => [
                    'required' => true, // valid
                    'length' => [1, 3], // not valid
                    'email' => true,    // not valid
                ],
            ],
            'names' => [
                'email' => 'Your email',
            ],
        ]);

        $messages = $validator->getSummaryMessages();
        foreach ($messages['email'] as $message) {
            $this->assertStringContainsString('Your email', $message);
        }
    }

    public function testJoinedMessage()
    {
        $validator = $this->validate([
            'data' => [
                'email' => 'error-email',
            ],
            'rules' => [
                'email' => [
                    'required' => true,         // valid
                    'length' => [1, 3],    // not valid
                    'email' => true,            // not valid
                    'endsWith' => '@gmail.com',  // not valid
                ],
            ],
            'messages' => [
                'email' => [
                    'length' => 'error message 1',
                    'email' => 'error message 2',
                    'endsWith' => 'error message 2', // the same message would be combined
                ],
            ],
        ]);

        $message = $validator->getJoinedMessage('|');
        $this->assertStringContainsString('error message 1', $message);
        $this->assertStringContainsString('error message 2', $message);
        $this->assertEquals('error message 1|error message 2', $message);
    }

    public function testGetFirstMessage()
    {
        $validator = $this->validate([
            'data' => [],
            'rules' => [
                'name' => [
                    'required' => false,
                ],
            ],
        ]);
        $this->assertFalse($validator->getFirstMessage());

        $validator = $this->validate([
            'data' => [],
            'rules' => [
                'name' => 'required',
                'email' => 'required',
            ],
            'messages' => [
                'name' => 'error message 1',
                'email' => 'error message 2',
            ],
        ]);
        $firstMessage = $validator->getFirstMessage();
        $this->assertEquals('error message 1', $firstMessage);
    }

    public function testGetFlatMessages()
    {
        $validator = $this->validate([
            'data' => [],
            'rules' => [
                'name' => 'required',
                'email' => 'required',
            ],
            'messages' => [
                'name' => 'error message 1',
                'email' => 'error message 2',
            ],
        ]);
        $messages = $validator->getFlatMessages();
        $this->assertSame([
            'name-required-required' => 'error message 1',
            'email-required-required' => 'error message 2',
        ], $messages);
    }

    public function testGetRuleValidator()
    {
        $validator = $this->validate([
            'data' => [
                'email' => 'error-email',
            ],
            'rules' => [
                'email' => [
                    'required' => true,         // valid
                    'length' => [1, 3],    // not valid
                    'email' => true,            // not valid
                ],
            ],
            'names' => [
                'email' => 'Your email',
            ],
        ]);

        $this->assertInstanceOf(IsRequired::class, $validator->getRuleValidator('email', 'required'));
        $this->assertInstanceOf(IsLength::class, $validator->getRuleValidator('email', 'length'));
        $this->assertInstanceOf(IsEmail::class, $validator->getRuleValidator('email', 'email'));
    }

    public function testGetNames()
    {
        $validator = $this->validate([
            'rules' => [
                'key' => 'required',
            ],
            'names' => [
                'key' => 'value',
            ],
        ]);

        $this->assertEquals(['key' => 'value'], $validator->getNames());
    }

    public function testInvalidRule()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->validate([
            'rules' => [
                'key' => new \stdClass(),
            ],
        ]);
    }

    public function testSetDetailMessageForValidator()
    {
        $validator = $this->validate([
            'data' => [
                'username' => 'test',
            ],
            'rules' => [
                'username' => [
                    'email' => true,    // not valid
                ],
            ],
            'messages' => [
                'username' => [
                    'email' => [
                        'format' => 'custom format message',
                    ],
                ],
            ],
        ]);

        $this->assertEquals(
            'custom format message',
            $validator->getRuleValidator('username', 'email')->getOption('formatMessage')
        );
    }

    public function testInvalidArgumentException()
    {
        $this->expectException(\UnexpectedValueException::class);

        $email = $this->validate->createRuleValidator('email', [
            'formatMessage' => '%noThisProperty%',
        ]);

        $email('not email');

        $email->getMessages();
    }

    public function testGetNotMessages()
    {
        $email = $this->validate->createRuleValidator('notEmail', [
            'negativeMessage' => 'this value must not be an email address',
        ]);

        $email('email@example.com');

        $this->assertContains('this value must not be an email address', $email->getMessages());
    }

    public function testSetMessages()
    {
        $email = $this->validate->createRuleValidator('email');

        $email->setMessages([
            'format' => 'please provide a valid email address',
        ]);

        $email('not email');

        $this->assertContains('please provide a valid email address', $email->getMessages());
    }

    public function testGetName()
    {
        $email = $this->validate->createRuleValidator('email');

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
        $validator = $this->validate([
            'data' => [
                'email' => 'twinhuang',
            ],
            'rules' => [
                'email' => [
                    'email' => true,
                ],
            ],
            'messages' => [
                'email' => 123,
            ],
        ]);
        $this->assertEquals(123, $validator->getFirstMessage());
    }

    public function testNullRules()
    {
        $validator = $this->validate([
            'rules' => null,
        ]);
        $this->assertTrue($validator->isValid());

        $validator = $this->validate([
            'rules' => null,
            'messages' => null,
        ]);
        $this->assertTrue($validator->isValid());
    }

    public function testValidateOptionalZeroValue()
    {
        $validator = $this->validate([
            'data' => [
                'email' => 0,
            ],
            'rules' => [
                'email' => [
                    'required' => false,
                    'email' => true,
                ],
            ],
        ]);

        $this->assertFalse($validator->isValid());
        $this->assertEquals(['email'], $validator->getInvalidRules('email'));
    }

    public function testGetValidatorMessages()
    {
        $result = $this->isLength('abc', 4, 5);
        $this->assertFalse($result);
        $this->assertStringContainsString('ABC', current($this->isLength->getMessages('ABC')));
        $this->assertStringContainsString('ABC', $this->isLength->getJoinedMessage("\n", 'ABC'));
        $this->assertEquals('This value', $this->isLength->getName());
    }

    public function testRequiredRuleNotBreakError()
    {
        $validator = $this->validate([
            'data' => [],
            'rules' => [
                'username' => [
                    'required' => true,
                    'minLength' => 3,
                ],
            ],
        ]);

        $this->assertFalse($validator->isValid());
        // not call yet
        $this->assertNull($validator->getRuleValidator('username', 'minLength'));
        $invalidRules = $validator->getInvalidRules('username');
        $this->assertEquals(['required'], $invalidRules);
    }

    public function testValidatorRuleGetFirstMessage()
    {
        $wei = $this->wei;
        $wei->isIn('apple', ['pear', 'orange']);

        $this->assertIsString($wei->isIn->getFirstMessage());
        $this->assertStringContainsString('name', $wei->isIn->getFirstMessage('name'));
    }

    public function testIgnoreClosure()
    {
        $validator = $this->validate([
            'data' => function () {
            },
            'rules' => [
                'username' => [
                    'required' => true,
                ],
            ],
        ]);

        $this->assertFalse($validator->isValid());
    }

    public function testGetCurrentRule()
    {
        $currentRules = [];
        $result = $this->validate([
            'data' => [
                'email' => 'twinhuang@qq.com',
                'age' => '5',
            ],
            'rules' => [
                'email' => [
                    'email' => true,
                ],
                'age' => [
                    'digit' => true,
                    'between' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
            ],
            'ruleValid' => function ($rule, $field, Validate $validator) use (&$currentRules) {
                $currentRules[] = $validator->getCurrentRule();
            },
        ])->isValid();

        $this->assertTrue($result);
        $this->assertSame(['required', 'email', 'required', 'digit', 'between'], $currentRules);
    }

    public function testCheck()
    {
        $ret = IsPositiveInteger::check(1, 'Number');
        $this->assertRetSuc($ret);
    }

    public function testCheckErr()
    {
        $ret = IsPositiveInteger::check(0, 'Number');
        $this->assertRetErr($ret, 'Number must be positive integer');
    }

    public function testCheckWithoutName()
    {
        $ret = IsPositiveInteger::check(0);
        $this->assertRetErr($ret, '%name% must be positive integer');
    }
}
