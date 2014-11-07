<?php

namespace Superruzafa\Rules\Expression\Operator\Comparison;

use Superruzafa\Rules\Expression\ExpressionTestAbstract;

class EqualToTest extends ExpressionTestAbstract
{
    /** @test */
    public function name()
    {
        $equalTo = new EqualTo();
        $this->assertEquals('equalTo', $equalTo->getName());
    }

    /** @test */
    public function noOperands()
    {
        $this->setExpectedException('LengthException');
        $equalTo = new EqualTo();
        $equalTo->evaluate();
    }

    /** @test */
    public function oneOperand()
    {
        $this->setExpectedException('LengthException');
        $equalTo = new EqualTo();
        $equalTo->addOperand($this->getExpressionMock())->evaluate();
    }

    /** @test */
    public function twoEqualOperands()
    {
        $equalTo = new EqualTo();
        $this->assertTrue($equalTo
            ->addOperand($this->getEvaluateMock('A'))
            ->addOperand($this->getEvaluateMock('A'))
            ->evaluate()
        );
    }

    /** @test */
    public function twoNonEqualOperands()
    {
        $equalTo = new EqualTo();
        $this->assertFalse($equalTo
            ->addOperand($this->getEvaluateMock('A'))
            ->addOperand($this->getEvaluateMock('B'))
            ->evaluate()
        );
    }

    /** @test */
    public function manyEqualOperands()
    {
        $operand = $this->getEvaluateMock(
            'A',
            $this->exactly(5)
        );
        $equalTo = new EqualTo();
        $this->assertTrue($equalTo
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->evaluate()
        );
    }

    /** @test */
    public function manyOperandsOneOfThemDifferentWorstCase()
    {
        $operand = $this->getEvaluateMock(
            $this->onConsecutiveCalls('A', 'A', 'A', 'A', 'B'),
            $this->exactly(5)
        );
        $equalTo = new EqualTo();
        $this->assertFalse($equalTo
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->evaluate()
        );
    }

    /** @test */
    public function manyOperandsOneOfThemDifferentBestCase()
    {
        $operand = $this->getEvaluateMock(
            $this->onConsecutiveCalls('B', 'A', 'A', 'A', 'A'),
            $this->exactly(2)
        );
        $equalTo = new EqualTo();
        $this->assertFalse($equalTo
                ->addOperand($operand)
                ->addOperand($operand)
                ->addOperand($operand)
                ->addOperand($operand)
                ->addOperand($operand)
                ->evaluate()
        );
    }

    /** @test */
    public function codeTwoEqualOperands()
    {
        $operand = $this->getNativeExpressionMock('A');
        $equalTo = new EqualTo($operand, $operand);
        $this->assertEquals('true', $equalTo->getNativeExpression());
    }

    /** @test */
    public function codeManyEqualOperands()
    {
        $operand = $this->getNativeExpressionMock('A');
        $equalTo = new EqualTo($operand, $operand, $operand, $operand, $operand);
        $this->assertEquals('true', $equalTo->getNativeExpression());
    }

    /** @test */
    public function codeTwoDifferentOperands()
    {
        $operand = $this->getNativeExpressionMock(
            $this->onConsecutiveCalls("'A'", "'B'")
        );
        $equalTo = new EqualTo($operand, $operand);
        $this->assertEquals("('A' == 'B')", $equalTo->getNativeExpression());
    }

    /** @test */
    public function codeManyDifferentOperands()
    {
        $operand = $this->getNativeExpressionMock(
            $this->onConsecutiveCalls("'A'", "'B'", "'C'", "'B'", "'A'")
        );
        $equalTo = new EqualTo($operand, $operand, $operand, $operand, $operand);
        $this->assertEquals("(('A' == 'B') && ('B' == 'C'))", $equalTo->getNativeExpression());
    }
}
