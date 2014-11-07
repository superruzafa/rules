<?php

namespace Superruzafa\Rules\Expression\Operator\Logical;

use Superruzafa\Rules\Expression\ExpressionTestAbstract;

class AndOpTest extends ExpressionTestAbstract
{
    /** @var AndOp */
    private $operator;

    protected function setUp()
    {
        $this->operator = new AndOp();
    }

    /** @test */
    public function name()
    {
        $this->assertEquals('and', $this->operator->getName());
    }

    /** @test */
    public function valueWithNoOperands()
    {
        $this->setExpectedException('RuntimeException');
        $this->operator->evaluate();
    }

    /** @test */
    public function codeWithNoOperands()
    {
        $this->setExpectedException('RuntimeException');
        $this->operator->getNativeExpression();
    }

    /** @test */
    public function valueWithATrueOperand()
    {
        $this->assertTrue($this->operator->addOperand($this->getEvaluateMock(true))->evaluate());
    }

    /** @test */
    public function valueWithAFalseOperand()
    {
        $this->assertFalse($this->operator->addOperand($this->getEvaluateMock(false))->evaluate());
    }

    /** @test */
    public function valueWithSeveralOperandsBestCase()
    {
        $operand = $this->getEvaluateMock(
            $this->onConsecutiveCalls(false, true, true, true, true),
            $this->exactly(1)
        );
        $result = $this->operator
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->evaluate();

        $this->assertFalse($result);
    }

    /** @test */
    public function valueWithSeveralOperandsWorstCase()
    {
        $operand = $this->getEvaluateMock(
            $this->onConsecutiveCalls(true, true, true, true, false),
            $this->exactly(5)
        );
        $result = $this->operator
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->evaluate();

        $this->assertFalse($result);
    }

    /** @test */
    public function codeWithOneOperand()
    {
        $operand = $this->getNativeExpressionMock('EXPRESSION');
        $this->operator->addOperand($operand);
        $this->assertEquals('EXPRESSION', $this->operator->getNativeExpression());
    }

    /** @test */
    public function codeWithSeveralEqualOperands()
    {
        $operand = $this->getNativeExpressionMock('EXPRESSION');
        $this->operator
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertEquals('EXPRESSION', $this->operator->getNativeExpression());
    }

    /** @test */
    public function codeWithSeveralOperands()
    {
        $this->operator
            ->addOperand($this->getNativeExpressionMock('EXPRESSION1'))
            ->addOperand($this->getNativeExpressionMock('EXPRESSION2'))
            ->addOperand($this->getNativeExpressionMock('EXPRESSION3'))
            ->addOperand($this->getNativeExpressionMock('EXPRESSION2'))
            ->addOperand($this->getNativeExpressionMock('EXPRESSION1'));
        $this->assertEquals('(EXPRESSION1 && EXPRESSION2 && EXPRESSION3)', $this->operator->getNativeExpression());
    }
}
