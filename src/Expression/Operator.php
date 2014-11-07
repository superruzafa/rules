<?php

namespace Superruzafa\Rules\Expression;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Superruzafa\Rules\Expression;

abstract class Operator implements Expression, \Countable
{
    /** @var Expression[] */
    protected $operands = array();

    /**
     * Creates a new Operator
     *
     * @param Expression $operand...
     */
    public function __construct()
    {
        $operands = func_get_args();
        array_walk($operands, function(&$operand) {
            if (!($operand instanceof Expression)) {
                $operand = Primitive::create($operand);
            }
        });
        $this->operands = $operands;
    }

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

    protected function checkOperandsCount($min = null, $max = null)
    {
        $count = count($this->operands);
        if (!is_null($min) && $count < $min) {
            throw new \LengthException(sprintf('%s needs at least %d operands', get_class($this), $min));
        } elseif (!is_null($max) && $max < $count) {
            throw new \LengthException(sprintf('%s needs as much %d operands', get_class($this), $max));
        }
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
