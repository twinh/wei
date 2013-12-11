<?php
namespace WeiTest\Validator;

class FieldExistsTest extends TestCase
{
    /**
     * @dataProvider providerForFieldExists
     */
    public function testFieldExists($data, $min, $max, $valid, $errorMessageName)
    {
        /** @var $validator \Wei\Validate */
        $validator = $this->validate(array(
            'data' => array(
                'submit' => 1
            ) + $data,
            'rules' => array(
                'mobile' => array(
                    'required' => false,
                    'mobileCn' => true
                ),
                'officePhone' => array(
                    'required' => false,
                    'phoneCn' => true
                ),
                'homePhone' => array(
                    'required' => false,
                    'phoneCn' => true
                ),
                'submit' => array(
                    'fieldExists' => array(
                        'fields' => array('mobile', 'officePhone', 'homePhone'),
                        'min' => $min,
                        'max' => $max
                    )
                )
            )
        ));

        $this->assertSame($valid, $validator->isValid());
        if (!$valid) {
            $this->assertTrue($validator->isFieldInvalid('submit'));
            $this->assertTrue($validator->getRuleValidator('submit', 'fieldExists')->hasError($errorMessageName));
        }
    }

    public function providerForFieldExists()
    {
        return array(
            array(
                'data' => array(
                    'mobile' => null,
                    'officePhone' => '0755-88888888',
                    'homePhone' => '0755-88888888'
                ),
                'min' => 1,
                'max' => null,
                'valid' => true,
                'errorMessageName' => null
            ),
            array(
                'data' => array(

                ),
                'min' => 1,
                'max' => null,
                'valid' => false,
                'errorMessageName' => 'tooFew'
            ),
            array(
                'data' => array(
                    'mobile' => '13800138000',
                    'officePhone' => '0755-88888888',
                    'homePhone' => '0755-88888888',
                    'submit' => true
                ),
                'min' => 1,
                'max' => 2,
                'valid' => false,
                'errorMessageName' => 'tooMany'
            )
        );
    }

    public function testLength()
    {
        /** @var $validator \Wei\Validate */
        $validator = $this->validate(array(
            'data' => array(
                'officePhone' => '0755-88888888',
                'homePhone' => null,
                'submit' => 1
            ),
            'rules' => array(
                'mobile' => array(
                    'required' => false,
                    'mobileCn' => true
                ),
                'officePhone' => array(
                    'required' => false,
                    'phoneCn' => true
                ),
                'homePhone' => array(
                    'required' => false,
                    'phoneCn' => true
                ),
                'submit' => array(
                    'fieldExists' => array(
                        'fields' => array('mobile', 'officePhone', 'homePhone'),
                        'length' => 1
                    )
                )
            )
        ));

        $this->assertTrue($validator->isValid());

        /** @var $validator \Wei\Validate */
        $validator = $this->validate(array(
            'data' => array(
                'officePhone' => '0755-88888888',
                'homePhone' => '0755-88888888',
                'submit' => 1
            ),
            'rules' => array(
                'mobile' => array(
                    'required' => false,
                    'mobileCn' => true
                ),
                'officePhone' => array(
                    'required' => false,
                    'phoneCn' => true
                ),
                'homePhone' => array(
                    'required' => false,
                    'phoneCn' => true
                ),
                'submit' => array(
                    'fieldExists' => array(
                        'fields' => array('mobile', 'officePhone', 'homePhone'),
                        'length' => 1
                    )
                )
            )
        ));

        $this->assertFalse($validator->isValid());
    }
}
