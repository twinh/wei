<?php

namespace Qwin\Test;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
	/**
     * @covers \Qwin\Test\A::add
     */
    public function test__invoke()
    {
    	$a = new A;
    	$this->assertEquals(3, $a->add(1, 2));
        $this->assertEquals(true, true);
    }
}

class A
{
	public function add($a, $b)
	{
		return $a + $b;
	}
}