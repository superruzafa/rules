<?php

namespace Superruzafa\Rules\Expression;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Superruzafa\Rules\Context;
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

    private function checkOperandsCount()
    {
        $this->defineOperandsCount($min, $max);

        $count = count($this->operands);
        if (!is_null($min) && $count < $min) {
            throw new \LengthException(sprintf('%s needs at least %d operands', get_class($this), $min));
        } elseif (!is_null($max) && $max < $count) {
            throw new \LengthException(sprintf('%s needs as much %d operands', get_class($this), $max));
        }
    }

    /**
     * Defines the minimum and maximum quantity of operands this operator could handle
     *
     * @param integer $min Operator's minimum number of operands
     * @param integer $max Operator's maximum number of operands
     */
    abstract protected function defineOperandsCount(&$min = 0, &$max = null);

    /**
     * Gets the name of the operator
     *
     * @return string
     */
    abstract public function getName();

    /** {@inheritdoc} */
    final public function evaluate(Context $context = null)
    {
        $this->checkOperandsCount();
        return $this->doEvaluate($context);
    }

    /**
     * Does the real evaluate job
     *
     * @param Context $context
     * @return mixed
     */
    abstract protected function doEvaluate(Context $context = null);

    /** {@inheritdoc} */
    final public function getNativeExpression()
    {
        $this->checkOperandsCount();
        return $this->doGetNativeExpression();
    }

    /**
     * Does the real native expression build job
     *
     * @return string
     */
    abstract protected function doGetNativeExpression();

    /** {@inheritdoc} */
    final public function count()
    {
        return count($this->operands);
    }
}
