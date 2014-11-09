<?php

namespace Superruzafa\Rules\Expression\Operator\Logical;

use Superruzafa\Rules\Context;
use Superruzafa\Rules\Expression;
use Superruzafa\Rules\Expression\Operator;

class NotOp extends Operator
{
    /** {@inheritdoc} */
    public function getName()
    {
        return 'not';
    }

    /** {@inheritdoc} */
    protected function defineOperandsCount(&$min = 0, &$max = null)
    {
        $min = 1;
    }

    /** {@inheritdoc} */
    protected function doEvaluate(Context $context = null)
    {
        foreach ($this->operands as $operand) {
            if ((bool)$operand->evaluate($context)) {
                return false;
            }
        }
        return true;
    }

    /** {@inheritdoc} */
    protected function doGetNativeExpression()
    {
        $operands = array_map(function (Expression $operand) {
            return $operand->getNativeExpression();
        }, $this->operands);
        $operands = array_values(array_unique($operands));

        return (1 == count($operands))
            ? sprintf('(!%s)', $operands[0])
            : sprintf('(!(%s))', implode(' || ', array_unique($operands)));
    }
}
