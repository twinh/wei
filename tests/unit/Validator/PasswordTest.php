<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class PasswordTest extends TestCase
{
    /**
     * @dataProvider providerForPassword
     * @param mixed $password
     */
    public function testPassword($password, array $options)
    {
        $validator = $this->validate([
            'data' => [
                'password' => $password,
            ],
            'rules' => [
                'password' => [
                    'password' => $options,
                ],
            ],
        ]);

        $this->assertTrue($validator->isValid());
        $this->assertTrue($this->wei->isPassword($password, $options));
    }

    /**
     * @dataProvider providerForNotPassword
     * @param mixed $password
     * @param mixed $messageTypes
     */
    public function testNotPassword($password, array $options, $messageTypes)
    {
        /** @var $validator \Wei\Validate */
        $validator = $this->validate([
            'data' => [
                'password' => $password,
            ],
            'rules' => [
                'password' => [
                    'password' => $options,
                ],
            ],
        ]);

        $messages = $validator->getDetailMessages();
        foreach ((array) $messageTypes as $type) {
            $this->assertArrayHasKey($type, $messages['password']['password']);
        }

        $this->assertFalse($validator->isValid());
        $this->assertFalse($this->wei->isPassword($password, $options));
    }

    public function providerForPassword()
    {
        return [
            [
                '123456',
                [],
            ],
            [
                '12345',
                [
                    'minLength' => 5,
                ],
            ],
            [
                '123456',
                [
                    'minLength' => 6,
                    'maxLength' => 8,
                ],
            ],
            [
                '123456',
                [
                    'needDigit' => true,
                ],
            ],
            [
                '123456a',
                [
                    'needLetter' => true,
                ],
            ],
            [
                '123456a@',
                [
                    'needNonAlnum' => true,
                ],
            ],
            [
                '123456abc',
                [
                    'needDigit' => true,
                    'needLetter' => true,
                ],
            ],
            [
                '123456abc中文',
                [
                    'needDigit' => true,
                    'needLetter' => true,
                    'needNonAlnum' => true,
                ],
            ],
            [
                '234',
                [
                    'atLeastPresent' => 1,
                ],
            ],
            [
                '234abc',
                [
                    'atLeastPresent' => 2,
                ],
            ],
            [
                'ab@#$c',
                [
                    'atLeastPresent' => 2,
                ],
            ],
            [
                '123abc@#$',
                [
                    'atLeastPresent' => 2,
                ],
            ],
            [
                '123abc@#$',
                [
                    'atLeastPresent' => 3,
                ],
            ],
        ];
    }

    public function providerForNotPassword()
    {
        return [
            [
                '12345',
                [
                    'minLength' => 6,
                ],
                'lengthTooShort',
            ],
            [
                '1234',
                [
                    'minLength' => 5,
                ],
                'lengthTooShort',
            ],
            [
                '123456',
                [
                    'maxLength' => 5,
                ],
                'lengthTooLong',
            ],
            [
                'abcdef',
                [
                    'needDigit' => true,
                ],
                'missingCharType',
            ],
            [
                '123456',
                [
                    'needLetter' => true,
                ],
                'missingCharType',
            ],
            [
                '123456a',
                [
                    'needNonAlnum' => true,
                ],
                'missingCharType',
            ],
            [
                '@#$',
                [
                    'needDigit' => true,
                    'needLetter' => true,
                ],
                'missingCharType',
            ],
            [
                '123456abc',
                [
                    'needDigit' => true,
                    'needLetter' => true,
                    'needNonAlnum' => true,
                ],
                'missingCharType',
            ],
            [
                '123',
                [
                    'atLeastPresent' => 2,
                ],
                'missingChar',
            ],
            [
                '123abc',
                [
                    'atLeastPresent' => 3,
                ],
                'missingChar',
            ],
            [
                'abc@#$',
                [
                    'atLeastPresent' => 3,
                ],
                'missingChar',
            ],
            [
                '123ABC',
                [
                    'atLeastPresent' => 3,
                ],
                'missingChar',
            ],
            [
                '123ABCabc',
                [
                    'atLeastPresent' => 4,
                ],
                'missingCharType',
            ],
            [
                '123@#$abc',
                [
                    'atLeastPresent' => 4,
                ],
                'missingCharType',
            ],
            [
                '123@#$',
                [
                    'atLeastPresent' => 4,
                ],
                'missingCharType',
            ],
            [
                '123',
                [
                    'atLeastPresent' => 4,
                ],
                'missingCharType',
            ],
        ];
    }
}
