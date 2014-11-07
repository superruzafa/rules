<?php

namespace Superruzafa\Rules\Expression\Primitive;

class FloatTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Superruzafa\Rules\Expression\Primitive\Float */
    private $float;

    protected function setUp()
    {
        $this->float = new Float();
    }

    /** @test */
    public function numericStringValue()
    {
        $this->assertSame(123.0, $this->float->setValue('123CANDEMOR')->evaluate());
    }

    /** @test */
    public function stringValue()
    {
        $this->assertSame(0.0, $this->float->setValue('CANDEMOR')->evaluate());
    }

    /** @test */
    public function integerValue()
    {
        $this->assertSame(-1234.0, $this->float->setValue(-1234)->evaluate());
    }

    /** @test */
    public function floatValue()
    {
        $this->assertSame(-12.34, $this->float->setValue(-12.34)->evaluate());
    }

    /** @test */
    public function octalValue()
    {
        $this->assertSame(63.0, $this->float->setValue(077)->evaluate());
    }

    /** @test */
    public function hexadecimalValue()
    {
        $this->assertSame(255.0, $this->float->setValue(0xFF)->evaluate());
    }

    /** @test */
    public function arrayValue()
    {
        $this->assertSame(1.0, $this->float->setValue(array(5, 5, 5, 5))->evaluate());
    }

    /** @test */
    public function resourceValue()
    {
        $this->assertInternalType(\PHPUnit_Framework_Constraint_IsType::TYPE_FLOAT, $this->float->setValue(fopen(__FILE__, 'r'))->evaluate());
    }

    /** @test */
    public function objectWithoutToStringValue()
    {
        $object = $this->getMock('stdClass');
        $this->assertSame(0.0, $this->float->setValue($object)->evaluate());
    }

    /** @test */
    public function objectWithToStringValue()
    {
        $object = $this->getMock('stdClass', array('__toString'));
        $object
            ->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('123'));
        $this->assertSame(123.0, $this->float->setValue($object)->evaluate());
    }

    /** @test */
    public function closureValue()
    {
        $this->assertSame(0.0, $this->float->setValue(function () {})->evaluate());
    }

    /** @test */
    public function stringNativeExpression()
    {
        $this->assertSame('0', $this->float->setValue("CAND'EM\\'OR")->getNativeExpression());
    }

    /** @test */
    public function integerNativeExpression()
    {
        $this->assertSame('-1234', $this->float->setValue(-1234)->getNativeExpression());
    }

    /** @test */
    public function floatNativeExpression()
    {
        $this->assertSame('-12.34', $this->float->setValue(-12.34)->getNativeExpression());
    }

    /** @test */
    public function octalNativeExpression()
    {
        $this->assertSame('63', $this->float->setValue(077)->getNativeExpression());
    }

    /** @test */
    public function hexadecimalNativeExpression()
    {
        $this->assertSame('255', $this->float->setValue(0xFF)->getNativeExpression());
    }

    /** @test */
    public function arrayNativeExpression()
    {
        $this->assertSame('1', $this->float->setValue(array(1, 2, 3, 4, 5))->getNativeExpression());
    }

    /** @test */
    public function resourceNativeExpression()
    {
        $this->assertRegExp('/^\\d+$/', $this->float->setValue(fopen(__FILE__, 'r'))->getNativeExpression());
    }
}
