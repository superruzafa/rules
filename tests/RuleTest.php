<?php

namespace Superruzafa\Rules;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function emptyRule()
    {
        $rule = new Rule();
        $this->assertTrue($rule->getCondition()->evaluate());
        $this->assertInstanceOf('Superruzafa\\Rules\\Action\\NoAction', $rule->getAction());
    }

    /** @test */
    public function initializedRule()
    {
        $condition = $this->getMockForAbstractClass('Superruzafa\\Rules\\Expression');
        $action = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action');

        $rule = new Rule($condition, $action);
        $this->assertSame($condition, $rule->getCondition());
        $this->assertSame($action, $rule->getAction());
    }

    /** @test */
    public function executeRuleWithFalseCondition()
    {
        $context = $this->getMock('Superruzafa\\Rules\\Context');

        $condition = $this->getMockForAbstractClass('Superruzafa\\Rules\\Expression', array('evaluate'));
        $condition
            ->expects($this->once())
            ->method('evaluate')
            ->with($context)
            ->will($this->returnValue(false));

        $action = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action', array('perform'));
        $action
            ->expects($this->never())
            ->method('perform');

        $subRule = $this->getMock('Superruzafa\\Rules\\Rule', array('execute'));
        $subRule
            ->expects($this->never())
            ->method('execute');

        $rule = new Rule();
        $rule
            ->setCondition($condition)
            ->setAction($action)
            ->appendRule($subRule)
            ->execute($context);
    }

    /** @test */
    public function executeRule()
    {
        $context = $this->getMock('Superruzafa\\Rules\\Context');
        $rule = new Rule();

        $condition = $this->getMockForAbstractClass('Superruzafa\\Rules\\Expression', array('evaluate'));
        $condition
            ->expects($this->once())
            ->method('evaluate')
            ->with($context)
            ->will($this->returnValue(true));

        $action = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action', array('perform'));
        $action
            ->expects($this->once())
            ->method('perform')
            ->with($context);

        $subRule = $this->getMock('Superruzafa\\Rules\\Rule', array('execute'));
        $subRule
            ->expects($this->once())
            ->method('execute')
            ->with($context);

        $rule
            ->setCondition($condition)
            ->setAction($action)
            ->appendRule($subRule)
            ->execute($context);
    }
}
