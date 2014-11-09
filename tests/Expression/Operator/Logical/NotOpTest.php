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
    public function evaluateTooFewOperands()
    {
        $this->setExpectedException('LengthException');
        $this->not->evaluate();
    }

    /** @test */
    public function evaluateTooMuchOperands()
    {
        $this->setExpectedException('LengthException');
        $operand = $this->getExpressionMock();
        $this->not
            ->addOperand($operand)
            ->addOperand($operand)
            ->evaluate();
    }

    /** @test */
    public function evaluateTrue()
    {
        $operand = $this->getEvaluateMock(true);
        $this->assertFalse($this->not->addOperand($operand)->evaluate());
    }

    /** @test */
    public function evaluateFalse()
    {
        $operand = $this->getEvaluateMock(false);
        $this->assertTrue($this->not->addOperand($operand)->evaluate());
    }

    /** @test */
    public function codeTooFewOperands()
    {
        $this->setExpectedException('LengthException');
        $this->not->getNativeExpression();
    }

    /** @test */
    public function codeTooMuchOperands()
    {
        $this->setExpectedException('LengthException');
        $operand = $this->getExpressionMock();
        $this->not
            ->addOperand($operand)
            ->addOperand($operand)
            ->getNativeExpression();
    }

    /** @test */
    public function getNativeExpression()
    {
        $operand = $this->getNativeExpressionMock('EXPRESSION');
        $this->assertEquals('(!EXPRESSION)', $this->not->addOperand($operand)->getNativeExpression());
    }
}
