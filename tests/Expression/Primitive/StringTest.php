<?php

namespace Superruzafa\Rules\Expression\Primitive;

class StringTest extends \PHPUnit_Framework_TestCase
{
    /** @var String */
    private $primitive;

    protected function setUp()
    {
        $this->primitive = new String();
    }

    /** @test */
    public function stringValue()
    {
        $this->assertSame('CANDEMOR', $this->primitive->setValue('CANDEMOR')->evaluate());
    }

    /** @test */
    public function integerValue()
    {
        $this->assertSame('-1234', $this->primitive->setValue(-1234)->evaluate());
    }

    /** @test */
    public function floatValue()
    {
        $this->assertSame('-12.34', $this->primitive->setValue(-12.34)->evaluate());
    }

    /** @test */
    public function octalValue()
    {
        $this->assertSame('63', $this->primitive->setValue(077)->evaluate());
    }

    /** @test */
    public function hexadecimalValue()
    {
        $this->assertSame('255', $this->primitive->setValue(0xFF)->evaluate());
    }

    /** @test */
    public function arrayValue()
    {
        $this->assertSame('Array', $this->primitive->setValue(array(1, 2, 3, 4, 5))->evaluate());
    }

    /** @test */
    public function resourceValue()
    {
        $this->assertRegExp('/^Resource id #\d+$/', $this->primitive->setValue(fopen(__FILE__, 'r'))->evaluate());
    }

    /** @test */
    public function objectWithoutToStringValue()
    {
        $object = $this
            ->getMockBuilder('stdClass')
            ->setMockClassName('STDCLASS_OBJECT')
            ->getMock();
        $this->assertSame('STDCLASS_OBJECT', $this->primitive->setValue($object)->evaluate());
    }

    /** @test */
    public function objectWithToStringValue()
    {
        $object = $this->getMock('stdClass', array('__toString'));
        $object
            ->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('OBJECT'));
        $this->assertSame('OBJECT', $this->primitive->setValue($object)->evaluate());
    }

    /** @test */
    public function closureValue()
    {
        $this->assertSame('Closure', $this->primitive->setValue(function () {})->evaluate());
    }

    /** @test */
    public function stringNativeExpression()
    {
        $this->assertSame("'CAND\\'EM\\\\\\'OR'", $this->primitive->setValue("CAND'EM\\'OR")->getNativeExpression());
    }

    /** @test */
    public function integerNativeExpression()
    {
        $this->assertSame("'-1234'", $this->primitive->setValue(-1234)->getNativeExpression());
    }

    /** @test */
    public function floatNativeExpression()
    {
        $this->assertSame("'-12.34'", $this->primitive->setValue(-12.34)->getNativeExpression());
    }

    /** @test */
    public function octalNativeExpression()
    {
        $this->assertSame("'63'", $this->primitive->setValue(077)->getNativeExpression());
    }

    /** @test */
    public function hexadecimalNativeExpression()
    {
        $this->assertSame("'255'", $this->primitive->setValue(0xFF)->getNativeExpression());
    }

    /** @test */
    public function arrayNativeExpression()
    {
        $this->assertSame("'Array'", $this->primitive->setValue(array(1, 2, 3, 4, 5))->getNativeExpression());
    }

    /** @test */
    public function resourceNativeExpression()
    {
        $this->assertRegExp("/^'Resource id #\\d+'$/", $this->primitive->setValue(fopen(__FILE__, 'r'))->getNativeExpression());
    }
}
