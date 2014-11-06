<?php

namespace Superruzafa\Rules\Expression;

class OperatorTest extends ExpressionTestAbstract
{
    /** @var Operator */
    private $operator;

    protected function setUp()
    {
        $this->operator = $this->getMockForAbstractClass('Superruzafa\\Rules\\Expression\\Operator');
    }

    /** @test */
    public function emptyOperator()
    {
        $this->assertCount(0, $this->operator);
    }

    /** @test */
    public function oneOperator()
    {
        $this->assertCount(1, $this->operator->addOperand($this->getExpressionMock('foo')));
    }

    /** @test */
    public function manyOperators()
    {
        $operand = $this->getExpressionMock('foo');
        $this->operator
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand)
            ->addOperand($operand);
        $this->assertCount(5, $this->operator);
    }
}
