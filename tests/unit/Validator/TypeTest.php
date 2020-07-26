<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class TypeTest extends TestCase
{
    protected $inputTestOptions = [
        'type' => 'string',
    ];

    /**
     * @dataProvider providerForType
     * @param mixed $input
     * @param mixed $type
     */
    public function testType($input, $type)
    {
        $this->assertTrue($this->isType->__invoke($input, $type));
    }

    /**
     * @dataProvider providerForNotType
     * @param mixed $input
     * @param mixed $type
     */
    public function testNotType($input, $type)
    {
        $this->assertFalse($this->isType->__invoke($input, $type));
    }

    public function testResource()
    {
        $res = $this->createResource();
        $this->assertIsResource($res);
        $this->assertTrue($this->isType->__invoke($res, 'resource'));
    }

    public function testGetMessages()
    {
        $type = $this->validate->createRuleValidator('type', [
            'typeMessage' => 'type message',
        ]);

        $type('string', 'float');

        $this->assertContains('type message', $type->getMessages());
    }

    public function providerForType()
    {
        $obj = new \stdClass();
        $res = $this->createResource();

        return [
            // is_xxx
            [[],  'array'],
            [true,     'bool'],
            [1.2,      'float'],
            [1,        'int'],
            [1,        'integer'],
            [null,     'null'],
            ['1',      'numeric'],
            [$obj,     'object'],
            [$res,     'resource'],
            [1,        'scalar'],
            [1.1,      'scalar'],
            ['str',    'scalar'],
            [true,     'scalar'],
            ['str',    'string'],
            // ctype_xxx
            ['abc',    'alnum'],
            ['a2B',    'alnum'],
            ['123',    'alnum'],
            ['dXy',    'alpha'],
            ['abc',    'alpha'],
            ["\n\r\t", 'cntrl'],
            ['10002',  'digit'],
            ['arf12',  'graph'],
            ['qiutoa', 'lower'],
            ['A#@%',   'print'],
            ['*&$()',  'punct'],
            ["\n\r\t", 'space'],
            ['LMNSDO', 'upper'],
            ['10BC9',  'xdigit'],
            // object
            [$obj,     'stdClass'],
        ];
    }

    public function providerForNotType()
    {
        $obj = new \stdClass();
        $res = $this->createResource();

        return [
            // is_xxx
            ['1',      'array'],
            ['true',   'bool'],
            ['1.2',    'float'],
            ['1',      'int'],
            ['1',      'integer'],
            ['null',   'null'],
            ['1-2',    'numeric'],
            ['1',      'object'],
            [1,        'resource'],
            [[],  'scalar'],
            [$obj,     'scalar'],
            [$res,     'scalar'],
            // ctype_xxx
            ['a bc',   'alnum'],
            ['-213a',  'alnum'],
            ['123',    'alpha'],
            ['a bc',   'alpha'],
            ['arf12',  'cntrl'],
            ['182.20', 'digit'],
            ['wsl!12', 'digit'],
            ["\n\r\t", 'graph'],
            ['aac123', 'lower'],
            ['QASsdk', 'lower'],
            ["sdf\n",  'print'],
            ['!@ # $', 'punct'],
            ["\narf1", 'space'],
            ['LWC13',  'upper'],
            ['AR1012', 'xdigit'],
        ];
    }
}
