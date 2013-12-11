<?php

namespace WeiTest\Validator;

class PasswordTest extends TestCase
{
    /**
     * @dataProvider providerForPassword
     */
    public function testPassword($password, array $options)
    {
        $validator = $this->validate(array(
            'data' => array(
                'password' => $password,
            ),
            'rules' => array(
                'password' => array(
                    'password' => $options
                )
            )
        ));

        $this->assertTrue($validator->isValid());
        $this->assertTrue($this->wei->isPassword($password, $options));
    }

    /**
     * @dataProvider providerForNotPassword
     */
    public function testNotPassword($password, array $options, $messageTypes)
    {
        /** @var $validator \Wei\Validate */
        $validator = $this->validate(array(
            'data' => array(
                'password' => $password,
            ),
            'rules' => array(
                'password' => array(
                    'password' => $options
                )
            )
        ));

        $messages = $validator->getDetailMessages();
        foreach ((array)$messageTypes as $type) {
            $this->assertArrayHasKey($type, $messages['password']['password']);
        }

        $this->assertFalse($validator->isValid());
        $this->assertFalse($this->wei->isPassword($password, $options));
    }

    public function providerForPassword()
    {
        return array(
            array(
                '123456',
                array(),
            ),
            array(
                '12345',
                array(
                    'minLength' => 5
                )
            ),
            array(
                '123456',
                array(
                    'minLength' => 6,
                    'maxLength' => 8
                )
            ),
            array(
                '123456',
                array(
                    'needDigit' => true,
                )
            ),
            array(
                '123456a',
                array(
                    'needLetter' => true
                )
            ),
            array(
                '123456a@',
                array(
                    'needNonAlnum' => true
                )
            ),
            array(
                '123456abc',
                array(
                    'needDigit' => true,
                    'needLetter' => true
                )
            ),
            array(
                '123456abc中文',
                array(
                    'needDigit' => true,
                    'needLetter' => true,
                    'needNonAlnum' => true,
                )
            ),
            array(
                '234',
                array(
                    'atLeastPresent' => 1
                ),
            ),
            array(
                '234abc',
                array(
                    'atLeastPresent' => 2
                ),
            ),
            array(
                'ab@#$c',
                array(
                    'atLeastPresent' => 2
                ),
            ),
            array(
                '123abc@#$',
                array(
                    'atLeastPresent' => 2
                ),
            ),
            array(
                '123abc@#$',
                array(
                    'atLeastPresent' => 3
                ),
            )
        );
    }

    public function providerForNotPassword()
    {
        return array(
            array(
                '12345',
                array(
                    'minLength' => 6
                ),
                'lengthTooShort'
            ),
            array(
                '1234',
                array(
                    'minLength' => 5
                ),
                'lengthTooShort'
            ),
            array(
                '123456',
                array(
                    'maxLength' => 5
                ),
                'lengthTooLong'
            ),
            array(
                'abcdef',
                array(
                    'needDigit' => true,
                ),
                'missingCharType'
            ),
            array(
                '123456',
                array(
                    'needLetter' => true
                ),
                'missingCharType'
            ),
            array(
                '123456a',
                array(
                    'needNonAlnum' => true
                ),
                'missingCharType'
            ),
            array(
                '@#$',
                array(
                    'needDigit' => true,
                    'needLetter' => true
                ),
                'missingCharType'
            ),
            array(
                '123456abc',
                array(
                    'needDigit' => true,
                    'needLetter' => true,
                    'needNonAlnum' => true,
                ),
                'missingCharType'
            ),
            array(
                '123',
                array(
                    'atLeastPresent' => 2
                ),
                'missingChar'
            ),
            array(
                '123abc',
                array(
                    'atLeastPresent' => 3
                ),
                'missingChar'
            ),
            array(
                'abc@#$',
                array(
                    'atLeastPresent' => 3
                ),
                'missingChar'
            ),
            array(
                '123ABC',
                array(
                    'atLeastPresent' => 3
                ),
                'missingChar'
            ),
            array(
                '123ABCabc',
                array(
                    'atLeastPresent' => 4
                ),
                'missingCharType'
            ),
            array(
                '123@#$abc',
                array(
                    'atLeastPresent' => 4
                ),
                'missingCharType'
            ),
            array(
                '123@#$',
                array(
                    'atLeastPresent' => 4
                ),
                'missingCharType'
            ),
            array(
                '123',
                array(
                    'atLeastPresent' => 4
                ),
                'missingCharType'
            )
        );
    }
}
