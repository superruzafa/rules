<?php

namespace Superruzafa\Rules;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function nonExistingEntry()
    {
        $context = new Context();
        $this->assertNull($context['foo']);
    }

    /** @test */
    public function setEntry()
    {
        $context = new Context();
        $context['foo'] = 'bar';
        $this->assertEquals('bar', $context['foo']);
    }

    /** @test */
    public function overrideEntry()
    {
        $context = new Context();
        $context['foo'] = 'bar';
        $context['xxx'] = 'yyy';
        $this->assertEquals('yyy', $context['xxx']);
    }

    /** @test */
    public function checkEntry()
    {
        $context = new Context();
        $context['foo'] = 'xxx';
        $this->assertTrue(isset($context['foo']));
    }

    /** @test */
    public function unsetEntry()
    {
        $context = new Context();
        $context['foo'] = 'xxx';
        $context['bar'] = 'yyy';
        unset($context['foo']);
        $this->assertNull($context['foo']);
        $this->assertEquals('yyy', $context['bar']);
    }

    /** @test */
    public function initializeContext()
    {
        $context = new Context(array('foo' => 'bar'));
        $this->assertEquals('bar', $context['foo']);
    }

    /** @test */
    public function mergeEmptyContexts()
    {
        $context = new Context();
        $this->assertCount(0, $context->override(new Context()));
    }

    /** @test */
    public function mergeContexts()
    {
        $context = new Context(array('foo' => 'bar'));
        $context->override(new Context(array('bar' => 'baz')));
        $this->assertEquals('bar', $context['foo']);
        $this->assertEquals('baz', $context['bar']);
    }

    /** @test */
    public function overrideContext()
    {
        $context = new Context(array('foo' => 'bar'));
        $context->override(new Context(array('foo' => 'jarl')));
        $this->assertEquals('jarl', $context['foo']);
    }

    /** @test */
    public function iterator()
    {
        $context = new Context(array('foo' => 'bar'));
        $iterator = $context->getIterator();
        $this->assertInstanceOf('IteratorAggregate', $iterator);
        $iterator->offsetExists('foo');
    }
}
