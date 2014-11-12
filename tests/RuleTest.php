<?php

namespace Superruzafa\Rules;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function emptyRule()
    {
        $rule = new Rule();
        $this->assertTrue($rule->getCondition()->evaluate());
        $this->assertInstanceOf('Superruzafa\\Rules\\Action\\NoAction', $rule->getAction(Rule::BEFORE_RULE));
        $this->assertInstanceOf('Superruzafa\\Rules\\Action\\NoAction', $rule->getAction(Rule::BEFORE_SUBRULES));
        $this->assertInstanceOf('Superruzafa\\Rules\\Action\\NoAction', $rule->getAction(Rule::AFTER_SUBRULES));
        $this->assertInstanceOf('Superruzafa\\Rules\\Action\\NoAction', $rule->getAction(Rule::AFTER_RULE));
    }

    /** @test */
    public function initializedRule()
    {
        $condition = $this->getMockForAbstractClass('Superruzafa\\Rules\\Expression');
        $action = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action');

        $rule = new Rule($condition, $action);
        $this->assertSame($condition, $rule->getCondition());
        $this->assertSame($action, $rule->getAction());
        $this->assertInstanceOf('Superruzafa\\Rules\\Action\\NoAction', $rule->getAction(Rule::BEFORE_RULE));
        $this->assertInstanceOf('Superruzafa\\Rules\\Action\\NoAction', $rule->getAction(Rule::BEFORE_SUBRULES));
        $this->assertSame($action, $rule->getAction(Rule::AFTER_SUBRULES));
        $this->assertInstanceOf('Superruzafa\\Rules\\Action\\NoAction', $rule->getAction(Rule::AFTER_RULE));
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
    public function performActionBeforeRule()
    {
        $context = new Context();

        $action = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action', array('perform'));
        $action
            ->expects($this->once())
            ->method('perform')
            ->with($context)
            ->will($this->returnCallback(function (Context $context) {
                $context['before-rule'] = true;
            }));

        $condition = $this->getMockForAbstractClass('Superruzafa\\Rules\\Expression', array('evaluate'));
        $condition
            ->expects($this->once())
            ->method('evaluate')
            ->with($context)
            ->will($this->returnCallback(function (Context $context) {
                \PHPUnit_Framework_Assert::assertTrue($context['before-rule']);
            }));

        $rule = new Rule();
        $rule
            ->setCondition($condition)
            ->setAction($action, Rule::BEFORE_RULE)
            ->execute($context);
    }

    /** @test */
    public function performActionBeforeSubrules()
    {
        $context = new Context();

        $condition = $this->getMockForAbstractClass('Superruzafa\\Rules\\Expression', array('evaluate'));
        $condition
            ->expects($this->once())
            ->method('evaluate')
            ->with($context)
            ->will($this->returnCallback(function (Context $context) {
                $context['before-subrule'] = true;
                return true;
            }));

        $action = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action', array('perform'));
        $action
            ->expects($this->once())
            ->method('perform')
            ->with($context)
            ->will($this->returnCallback(function (Context $context) {
                \PHPUnit_Framework_Assert::assertTrue($context['before-subrule']);
            }));

        $rule = new Rule();
        $rule
            ->setCondition($condition)
            ->setAction($action, Rule::BEFORE_SUBRULES)
            ->execute($context);
    }

    /** @test */
    public function performActionAfterSubrules()
    {
        $context = new Context();

        $subruleAction = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action', array('perform'));
        $subruleAction
            ->expects($this->once())
            ->method('perform')
            ->with($context)
            ->will($this->returnCallback(function (Context $context) {
                $context['after-subrules'] = true;
                return $context;
            }));
        $subrule = new Rule();
        $subrule->setAction($subruleAction);

        $action = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action', array('perform'));
        $action
            ->expects($this->once())
            ->method('perform')
            ->with($context)
            ->will($this->returnCallback(function (Context $context) {
                \PHPUnit_Framework_Assert::assertTrue($context->offsetExists('after-subrules'));
            }));

        $rule = new Rule();
        $rule
            ->setAction($action, Rule::AFTER_SUBRULES)
            ->appendRule($subrule)
            ->execute($context);
    }

    /** @test */
    public function performActionAfterRule()
    {
        $context = new Context();

        $condition = $this->getMockForAbstractClass('Superruzafa\\Rules\\Expression', array('evaluate'));
        $condition
            ->expects($this->once())
            ->method('evaluate')
            ->with($context)
            ->will($this->returnValue(false));

        $subruleAction = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action', array('perform'));
        $subruleAction
            ->expects($this->never())
            ->method('perform');
        $subrule = new Rule();
        $subrule->setAction($subruleAction);

        $action = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action', array('perform'));
        $action
            ->expects($this->once())
            ->method('perform')
            ->with($context)
            ->will($this->returnCallback(function (Context $context) {
                \PHPUnit_Framework_Assert::assertFalse($context->offsetExists('after-subrules'));
            }));

        $rule = new Rule();
        $rule
            ->setCondition($condition)
            ->setAction($action, Rule::AFTER_RULE)
            ->appendRule($subrule)
            ->execute($context);
    }

    /** @test */
    public function appendActions()
    {
        $action1 = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action');
        $action2 = $this->getMockForAbstractClass('Superruzafa\\Rules\\Action');

        $rule = new Rule();
        $rule->appendAction($action1);
        $this->assertSame($action1, $rule->getAction());

        $rule->appendAction($action2);
        $action = $rule->getAction();
        $this->assertInstanceOf('Superruzafa\\Rules\\Action\\Sequence', $action);
        $iterator = $action->getIterator();
        $this->assertSame($action1, current($iterator));
        next($iterator);
        $this->assertSame($action2, current($iterator));
    }
}
