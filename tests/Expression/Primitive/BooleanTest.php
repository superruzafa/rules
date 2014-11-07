<?php

namespace Superruzafa\Rules\Expression\Primitive;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Superruzafa\Rules\Expression\Primitive\Boolean */
    private $primitive;

    protected function setUp()
    {
        $this->primitive = new Boolean();
    }

    public function falseProvider()
    {
        return array(
            array(false),
            array(null),
            array(''),
            array('0'),
            array(0),
            array(0.0),
            array(000),
            array(0x00),
            array(array()),
        );
    }

    public function trueProvider()
    {
        return array(
            array(true),
            array('1'),
            array(1),
            array(1.1),
            array(01),
            array(0x01),
            array(fopen(__FILE__, 'r')),
            array(function() {}),
            array((object)array())
        );
    }

    /**
     * @test
     * @dataProvider falseProvider
     *
     * @param mixed $value
     */
    public function falseValue($value)
    {
        $this->assertFalse($this->primitive->setValue($value)->evaluate());
    }

    /**
     * @test
     * @dataProvider falseProvider
     *
     * @param mixed $value
     */
    public function falseNativeExpression($value)
    {
        $this->assertSame('false', $this->primitive->setValue($value)->getNativeExpression());
    }

    /**
     * @test
     * @dataProvider trueProvider
     *
     * @param mixed $value
     */
    public function trueValue($value)
    {
        $this->assertTrue($this->primitive->setValue($value)->evaluate());
    }

    /**
     * @test
     * @dataProvider trueProvider
     *
     * @param mixed $value
     */
    public function trueNativeExpression($value)
    {
        $this->assertSame('true', $this->primitive->setValue($value)->getNativeExpression());
    }

    /** @test */
    public function trueBoolean()
    {
        $this->assertTrue(Boolean::true()->evaluate());
    }

    /** @test */
    public function falseBoolean()
    {
        $this->assertFalse(Boolean::false()->evaluate());
    }
}
