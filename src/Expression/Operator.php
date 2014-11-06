<?php

namespace Superruzafa\Rules\Expression;

use Superruzafa\Rules\Expression;

abstract class Operator implements Expression, \Countable
{
    /** @var Expression[] */
    protected $operands = array();

    /**
     * Adds an operand
     *
     * @param Expression $operand
     * @return Operator
     */
    public function addOperand(Expression $operand)
    {
        $this->operands[] = $operand;
        return $this;
    }

    /**
     * Gets the name of the operator
     *
     * @return string
     */
    abstract public function getName();

    /** {@inheritdoc} */
    final public function count()
    {
        return count($this->operands);
    }
}
