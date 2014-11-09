<?php

namespace Superruzafa\Rules\Expression\Operator\Logical;

use Superruzafa\Rules\Expression\ExpressionTestAbstract;

class NotOpTest extends ExpressionTestAbstract
{
    /** @var NotOp */
    private $not;

    protected function setUp()
    {
        $this->not = new NotOp();
    }

    /** @test */
    public function name()
    {
        $this->assertEquals('not', $this->not->getName());
    }

    /** @test */
    public function evaluateTooFewOperands()
    {
        $this->setExpectedException('LengthException');
        $this->not->evaluate();
    }

    /** @test */
    public function evaluateAllTrueOperands()
    {
        $operand = $this->getEvaluateMock(true);
        $this->not
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertFalse($this->not->evaluate());
    }

    /** @test */
    public function evaluateAllFalseOperands()
    {
        $operand = $this->getEvaluateMock(false);
        $this->not
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertTrue($this->not->evaluate());
    }

    /** @test */
    public function evaluateMixCaseOperands()
    {
        $operand = $this->getEvaluateMock($this->onConsecutiveCalls(false, false, false, true));
        $this->not
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertFalse($this->not->evaluate());
    }

    /** @test */
    public function codeTooFewOperands()
    {
        $this->setExpectedException('LengthException');
        $this->not->getNativeExpression();
    }

    /** @test */
    public function codeOneOperand()
    {
        $operand = $this->getNativeExpressionMock('EXP');
        $this->assertEquals('(!EXP)', $this->not->addOperand($operand)->getNativeExpression());
    }

    /** @test */
    public function codeSeveralOperands()
    {
        $operand = $this->getNativeExpressionMock($this->onConsecutiveCalls('EXP1', 'EXP2', 'EXP3', 'EXP2', 'EXP1'));
        $this->not
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertEquals('(!(EXP1 || EXP2 || EXP3))', $this->not->getNativeExpression());
    }
}
