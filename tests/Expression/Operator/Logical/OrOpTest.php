<?php

namespace Superruzafa\Rules\Expression\Operator\Logical;

use Superruzafa\Rules\Expression\ExpressionTestAbstract;

class OrOpTest extends ExpressionTestAbstract
{
    /** @var OrOp */
    private $or;

    protected function setUp()
    {
        $this->or = new OrOp();
    }

    /** @test */
    public function name()
    {
        $this->assertEquals('or', $this->or->getName());
    }

    /** @test */
    public function valueWithNoOperands()
    {
        $this->setExpectedException('LengthException');
        $this->or->evaluate();
    }

    /** @test */
    public function codeWithNoOperands()
    {
        $this->setExpectedException('LengthException');
        $this->or->getNativeExpression();
    }

    /** @test */
    public function valueWithATrueOperand()
    {
        $this->assertTrue($this->or->addOperand($this->getEvaluateMock(true))->evaluate());
    }

    /** @test */
    public function valueWithAFalseOperand()
    {
        $this->assertFalse($this->or->addOperand($this->getEvaluateMock(false))->evaluate());
    }

    /** @test */
    public function valueWithSeveralOperandsBestCase()
    {
        $operand = $this->getEvaluateMock(
            $this->onConsecutiveCalls(true, false, false, false, false),
            $this->exactly(1)
        );
        $result = $this->or
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->evaluate();

        $this->assertTrue($result);
    }

    /** @test */
    public function valueWithSeveralOperandsWorstCase()
    {
        $operand = $this->getEvaluateMock(
            $this->onConsecutiveCalls(false, false, false, false, true),
            $this->exactly(5)
        );
        $result = $this->or
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->evaluate();

        $this->assertTrue($result);
    }

    /** @test */
    public function codeWithOneOperand()
    {
        $operand = $this->getNativeExpressionMock('EXPRESSION');
        $this->or->addOperand($operand);
        $this->assertEquals('EXPRESSION', $this->or->getNativeExpression());
    }

    /** @test */
    public function codeWithSeveralEqualOperands()
    {
        $operand = $this->getNativeExpressionMock('EXPRESSION');
        $this->or
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertEquals('EXPRESSION', $this->or->getNativeExpression());
    }

    /** @test */
    public function codeWithSeveralOperands()
    {
        $this->or
            ->addOperand($this->getNativeExpressionMock('EXPRESSION1'))
            ->addOperand($this->getNativeExpressionMock('EXPRESSION2'))
            ->addOperand($this->getNativeExpressionMock('EXPRESSION3'))
            ->addOperand($this->getNativeExpressionMock('EXPRESSION2'))
            ->addOperand($this->getNativeExpressionMock('EXPRESSION1'));
        $this->assertEquals('(EXPRESSION1 || EXPRESSION2 || EXPRESSION3)', $this->or->getNativeExpression());
    }
}
