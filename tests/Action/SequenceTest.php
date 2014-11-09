<?php

namespace Superruzafa\Rules\Action;

use Superruzafa\Rules\Action;
use Superruzafa\Rules\Context;

class SequenceTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function invalidActionInConstructor()
    {
        $this->setExpectedException('\InvalidArgumentException');
        new Sequence(new \stdClass);
    }

    /** @test */
    public function actionsInConstructor()
    {
        $context = new Context();
        list($action1, $action2) = $this->getActionsMock($context);
        $sequence = new Sequence($action1, $action2);
        $sequence->perform($context);
    }

    /** @test */
    public function appendedActions()
    {
        $context = new Context();
        list($action1, $action2) = $this->getActionsMock($context);
        $sequence = new Sequence();
        $sequence->appendAction($action1)->appendAction($action2)->perform($context);
    }

    /**
     * Build two depending actions
     *
     * @param Context $context
     * @return Action[]|\PHPUnit_Framework_MockObject_MockObject[]
     */
    private function getActionsMock($context)
    {
        $action1 = $this->getMock('Superruzafa\\Rules\\Action', array('perform'));
        $action1
            ->expects($this->once())
            ->method('perform')
            ->with($context)
            ->will($this->returnCallback(function (Context $context) {
                $context['first-action'] = true;
            }));
        $action2 = $this->getMock('Superruzafa\\Rules\\Action', array('perform'));
        $action2
            ->expects($this->once())
            ->method('perform')
            ->with($context)
            ->will($this->returnCallback(function (Context $context) {
                \PHPUnit_Framework_Assert::assertTrue($context->offsetExists('first-action'));
            }));
        return array($action1, $action2);
    }
}
