<?php

namespace Superruzafa\Rules\Expression;

use Superruzafa\Rules\Expression\Primitive;

class PrimitiveTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function createString()
    {
        $string = Primitive::create('xxx');
        $this->assertInstanceOf('Superruzafa\\Rules\\Expression\\Primitive\\String', $string);
        $this->assertEquals('xxx', $string->evaluate());
    }

    /** @test */
    public function createInteger()
    {
        $string = Primitive::create(123);
        $this->assertInstanceOf('Superruzafa\\Rules\\Expression\\Primitive\\Integer', $string);
        $this->assertEquals(123, $string->evaluate());
    }

    /** @test */
    public function createFloat()
    {
        $string = Primitive::create(666.123);
        $this->assertInstanceOf('Superruzafa\\Rules\\Expression\\Primitive\\Float', $string);
        $this->assertEquals(666.123, $string->evaluate());
    }

    /** @test */
    public function createBoolean()
    {
        $string = Primitive::create(false);
        $this->assertInstanceOf('Superruzafa\\Rules\\Expression\\Primitive\\Boolean', $string);
        $this->assertFalse($string->evaluate());
    }
}
