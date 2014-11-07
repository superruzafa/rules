<?php

namespace Superruzafa\Rules\Action;

class NoActionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function noAction()
    {
        $context = $this->getMock('Superruzafa\\Rules\\Context', array('override'));
        $context
            ->expects($this->never())
            ->method('override');
        $action = new NoAction();
        $this->assertNull($action->perform($context));
    }
}
