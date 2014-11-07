<?php

namespace Superruzafa\Rules\Action;

class OverrideContextTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function pepe()
    {
        $overridingContext = $this->getMock('Superruzafa\\Rules\\Context');
        $overridingContext
            ->expects($this->never())
            ->method('override');

        $overridableContext = $this->getMock('Superruzafa\\Rules\\Context');
        $overridableContext
            ->expects($this->once())
            ->method('override')
            ->with($overridingContext)
            ->will($this->returnSelf());

        $action = new OverrideContext($overridingContext);
        $this->assertSame($overridableContext, $action->perform($overridableContext));
    }
}
