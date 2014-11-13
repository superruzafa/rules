<?php

namespace Superruzafa\Rules\Expression\Primitive;

use Superruzafa\Rules\Context;

class StringTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Superruzafa\Rules\Expression\Primitive\String */
    private $string;

    protected function setUp()
    {
        $this->string = new String();
    }

    /** @test */
    public function stringValue()
    {
        $this->assertSame('CANDEMOR', $this->string->setValue('CANDEMOR')->evaluate());
    }

    /** @test */
    public function integerValue()
    {
        $this->assertSame('-1234', $this->string->setValue(-1234)->evaluate());
    }

    /** @test */
    public function floatValue()
    {
        $this->assertSame('-12.34', $this->string->setValue(-12.34)->evaluate());
    }

    /** @test */
    public function octalValue()
    {
        $this->assertSame('63', $this->string->setValue(077)->evaluate());
    }

    /** @test */
    public function hexadecimalValue()
    {
        $this->assertSame('255', $this->string->setValue(0xFF)->evaluate());
    }

    /** @test */
    public function arrayValue()
    {
        $this->assertSame('Array', $this->string->setValue(array(1, 2, 3, 4, 5))->evaluate());
    }

    /** @test */
    public function resourceValue()
    {
        $this->assertRegExp('/^Resource id #\d+$/', $this->string->setValue(fopen(__FILE__, 'r'))->evaluate());
    }

    /** @test */
    public function objectWithoutToStringValue()
    {
        $object = $this
            ->getMockBuilder('stdClass')
            ->setMockClassName('STDCLASS_OBJECT')
            ->getMock();
        $this->assertSame('STDCLASS_OBJECT', $this->string->setValue($object)->evaluate());
    }

    /** @test */
    public function objectWithToStringValue()
    {
        $object = $this->getMock('stdClass', array('__toString'));
        $object
            ->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('OBJECT'));
        $this->assertSame('OBJECT', $this->string->setValue($object)->evaluate());
    }

    /** @test */
    public function closureValue()
    {
        $this->assertSame('Closure', $this->string->setValue(function () {})->evaluate());
    }

    /** @test */
    public function stringNativeExpression()
    {
        $this->assertSame("'CAND\\'EM\\\\\\'OR'", $this->string->setValue("CAND'EM\\'OR")->getNativeExpression());
    }

    /** @test */
    public function integerNativeExpression()
    {
        $this->assertSame("'-1234'", $this->string->setValue(-1234)->getNativeExpression());
    }

    /** @test */
    public function floatNativeExpression()
    {
        $this->assertSame("'-12.34'", $this->string->setValue(-12.34)->getNativeExpression());
    }

    /** @test */
    public function octalNativeExpression()
    {
        $this->assertSame("'63'", $this->string->setValue(077)->getNativeExpression());
    }

    /** @test */
    public function hexadecimalNativeExpression()
    {
        $this->assertSame("'255'", $this->string->setValue(0xFF)->getNativeExpression());
    }

    /** @test */
    public function arrayNativeExpression()
    {
        $this->assertSame("'Array'", $this->string->setValue(array(1, 2, 3, 4, 5))->getNativeExpression());
    }

    /** @test */
    public function resourceNativeExpression()
    {
        $this->assertRegExp("/^'Resource id #\\d+'$/", $this->string->setValue(fopen(__FILE__, 'r'))->getNativeExpression());
    }

    /** @test */
    public function interpolation()
    {
        $context = new Context(array('variable' => 'BANG!'));
        $this->assertEquals('>>>BANG!<<<', $this->string->setValue('>>>{{ variable }}<<<')->evaluate($context));
    }

    /** @test */
    public function unneededInterpolation()
    {
        $context = new Context(array('variable' => 'BANG!'));
        $this->assertEquals('>>><<<', $this->string->setValue('>>><<<')->evaluate($context));
    }
}
