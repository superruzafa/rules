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
    public function invalidOperands()
    {
        $operand = $this->getExpressionMock();
        $noOperand = new \stdClass();
        $this->setExpectedException('InvalidArgumentException');
        $this
            ->getMockBuilder('Superruzafa\\Rules\\Expression\\Operator')
            ->setConstructorArgs(array($operand, $operand, $noOperand, $operand))
            ->getMockForAbstractClass();
    }

    /** @test */
    public function validOperands()
    {
        $operand = $this->getExpressionMock();
        $operator = $this
            ->getMockBuilder('Superruzafa\\Rules\\Expression\\Operator')
            ->setConstructorArgs(array($operand, $operand, $operand))
            ->getMockForAbstractClass();
        $this->assertCount(3, $operator);
    }

    /** @test */
    public function oneOperand()
    {
        $this->assertCount(1, $this->operator->addOperand($this->getExpressionMock('foo')));
    }

    /** @test */
    public function manyOperands()
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
