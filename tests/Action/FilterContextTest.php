<?php

namespace Superruzafa\Rules\Action;

use Superruzafa\Rules\Context;

class FilterContextTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function defaultMode()
    {
        $filter = new FilterContext();
        $this->assertEquals(FilterContext::ALLOW_KEYS, $filter->getMode());
        $this->assertEquals(array(), $filter->getKeys());
    }

    /** @test */
    public function testKeysAsArguments()
    {
        $filter = new FilterContext();
        $filter->setKeys('a', 'b', 'c', 'd');
        $this->assertEquals(array('a', 'b', 'c', 'd'), $filter->getKeys());
    }

    /** @test */
    public function testKeysAsArray()
    {
        $filter = new FilterContext();
        $array = array('a', 'b', 'c', 'd');
        $filter->setKeys($array);
        $this->assertEquals($array, $filter->getKeys());
    }

    /** @test */
    public function allowKeys()
    {
        $context = new Context(array(
            'foo' => 'bar',
            'xxx' => 'yyy',
        ));

        $filter = new FilterContext();
        $filter
            ->setKeys('foo')
            ->setMode(FilterContext::ALLOW_KEYS)
            ->perform($context);

        $this->assertTrue($context->offsetExists('foo'));
        $this->assertFalse($context->offsetExists('xxx'));
    }

    /** @test */
    public function disallowKeys()
    {
        $context = new Context(array(
            'foo' => 'bar',
            'xxx' => 'yyy',
        ));

        $filter = new FilterContext();
        $filter
            ->setKeys('foo')
            ->setMode(FilterContext::DISALLOW_KEYS)
            ->perform($context);

        $this->assertFalse($context->offsetExists('foo'));
        $this->assertTrue($context->offsetExists('xxx'));
    }

}
