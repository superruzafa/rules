<?php

namespace Superruzafa\Rules\Action;

use Superruzafa\Rules\Context;

class RunCallbackTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function invalidCallback()
    {
        $this->setExpectedException('\\InvalidArgumentException');
        new RunCallback('pepe');
    }

    /** @test */
    public function anonymousCallback()
    {
        $context = $this->getMock('Superruzafa\\Rules\\Context');

        $flag = 0;
        $callback = function(Context $ctx) use (&$flag, $context) {
            $flag = 1;
            \PHPUnit_Framework_Assert::assertSame($ctx, $context);
            return 2;
        };
        $action = new RunCallback($callback);
        $this->assertEquals(2, $action->perform($context));
        $this->assertEquals(1, $flag);
    }

    /** @test */
    public function arrayCallback()
    {
        $context = $this->getMock('Superruzafa\\Rules\\Context');
        $object = $this->getMock('\\stdClass', array('method'));
        $object
            ->expects($this->once())
            ->method('method')
            ->with($context)
            ->will($this->returnValue('xxx'));

        $action = new RunCallback(array($object, 'method'));
        $this->assertEquals('xxx', $action->perform($context));
    }

    public static function staticMethod(Context $context)
    {
        return 'yyy';
    }

    /** @test */
    public function staticCallback()
    {
        $context = $this->getMock('Superruzafa\\Rules\\Context');
        $action = new RunCallback(sprintf('%s::staticMethod', get_class($this)));
        $this->assertEquals('yyy', $action->perform($context));
    }
}
