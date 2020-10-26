<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class FieldExistsTest extends TestCase
{
    /**
     * @dataProvider providerForFieldExists
     * @param mixed $data
     * @param mixed $min
     * @param mixed $max
     * @param mixed $valid
     * @param mixed $errorMessageName
     */
    public function testFieldExists($data, $min, $max, $valid, $errorMessageName)
    {
        /** @var $validator \Wei\Validate */
        $validator = $this->validate([
            'data' => [
                'submit' => 1,
            ] + $data,
            'rules' => [
                'mobile' => [
                    'required' => false,
                    'mobileCn' => true,
                ],
                'officePhone' => [
                    'required' => false,
                    'phoneCn' => true,
                ],
                'homePhone' => [
                    'required' => false,
                    'phoneCn' => true,
                ],
                'submit' => [
                    'fieldExists' => [
                        'fields' => ['mobile', 'officePhone', 'homePhone'],
                        'min' => $min,
                        'max' => $max,
                    ],
                ],
            ],
        ]);

        $this->assertSame($valid, $validator->isValid());
        if (!$valid) {
            $this->assertTrue($validator->isFieldInvalid('submit'));
            $this->assertTrue($validator->getRuleValidator('submit', 'fieldExists')->hasError($errorMessageName));
        }
    }

    public function providerForFieldExists()
    {
        return [
            [
                'data' => [
                    'officePhone' => '0755-88888888',
                    'homePhone' => '0755-88888888',
                ],
                'min' => 1,
                'max' => null,
                'valid' => true,
                'errorMessageName' => null,
            ],
            [
                'data' => [
                ],
                'min' => 1,
                'max' => null,
                'valid' => false,
                'errorMessageName' => 'tooFew',
            ],
            [
                'data' => [
                    'mobile' => '13800138000',
                    'officePhone' => '0755-88888888',
                    'homePhone' => '0755-88888888',
                    'submit' => true,
                ],
                'min' => 1,
                'max' => 2,
                'valid' => false,
                'errorMessageName' => 'tooMany',
            ],
        ];
    }

    public function testLength()
    {
        /** @var $validator \Wei\Validate */
        $validator = $this->validate([
            'data' => [
                'officePhone' => '0755-88888888',
                'submit' => 1,
            ],
            'rules' => [
                'mobile' => [
                    'required' => false,
                    'mobileCn' => true,
                ],
                'officePhone' => [
                    'required' => false,
                    'phoneCn' => true,
                ],
                'homePhone' => [
                    'required' => false,
                    'phoneCn' => true,
                ],
                'submit' => [
                    'fieldExists' => [
                        'fields' => ['mobile', 'officePhone', 'homePhone'],
                        'length' => 1,
                    ],
                ],
            ],
        ]);

        $this->assertTrue($validator->isValid());

        /** @var $validator \Wei\Validate */
        $validator = $this->validate([
            'data' => [
                'officePhone' => '0755-88888888',
                'homePhone' => '0755-88888888',
                'submit' => 1,
            ],
            'rules' => [
                'mobile' => [
                    'required' => false,
                    'mobileCn' => true,
                ],
                'officePhone' => [
                    'required' => false,
                    'phoneCn' => true,
                ],
                'homePhone' => [
                    'required' => false,
                    'phoneCn' => true,
                ],
                'submit' => [
                    'fieldExists' => [
                        'fields' => ['mobile', 'officePhone', 'homePhone'],
                        'length' => 1,
                    ],
                ],
            ],
        ]);

        $this->assertFalse($validator->isValid());
    }
}
