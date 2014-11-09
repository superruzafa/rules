<?php

namespace Superruzafa\Rules\Expression\Operator\Comparison;

use Superruzafa\Rules\Expression\ExpressionTestAbstract;

class NotEqualToTest extends ExpressionTestAbstract
{
    /** @var NotEqualTo */
    private $notEqualTo;

    protected function setUp()
    {
        $this->notEqualTo = new NotEqualTo();
    }

    /** @test */
    public function name()
    {
        $notEqualTo = new NotEqualTo();
        $this->assertEquals('notEqualTo', $notEqualTo->getName());
    }

    /** @test */
    public function tooFewOperands()
    {
        $this->setExpectedException('\LengthException');
        $this->notEqualTo->addOperand($this->getExpressionMock())->evaluate();
    }

    /** @test */
    public function evaluateEqualOperands()
    {
        $operand = $this->getEvaluateMock('xxx');
        $this->notEqualTo
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertFalse($this->notEqualTo->evaluate());
    }

    /** @test */
    public function evaluateNonEqualOperands()
    {
        $operand = $this->getEvaluateMock($this->onConsecutiveCalls('xxx', 'yyy', 'zzz'));
        $this->notEqualTo
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertTrue($this->notEqualTo->evaluate());
    }

    /** @test */
    public function codeEqualOperands()
    {
        $operand = $this->getNativeExpressionMock($this->onConsecutiveCalls('xxx', 'xxx', 'xxx'));
        $this->notEqualTo
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertEquals('false', $this->notEqualTo->getNativeExpression());
    }

    /** @test */
    public function codeTwoNonEqualOperands()
    {
        $operand = $this->getNativeExpressionMock($this->onConsecutiveCalls('xxx', 'xxx', 'yyy'));
        $this->notEqualTo
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertEquals('(xxx != yyy)', $this->notEqualTo->getNativeExpression());
    }

    /** @test */
    public function codeThreeNonEqualOperands()
    {
        $operand = $this->getNativeExpressionMock($this->onConsecutiveCalls('xxx', 'yyy', 'zzz'));
        $this->notEqualTo
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertEquals('((xxx != yyy) && (yyy != zzz))', $this->notEqualTo->getNativeExpression());
    }
}
