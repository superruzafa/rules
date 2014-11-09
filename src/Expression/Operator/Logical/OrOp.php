<?php

namespace Superruzafa\Rules\Expression\Operator\Logical;

use Superruzafa\Rules\Context;
use Superruzafa\Rules\Expression;
use Superruzafa\Rules\Expression\Operator;

class OrOp extends Operator
{
    /** {@inheritdoc} */
    public function getName()
    {
        return 'or';
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
                return true;
            }
        }
        return false;
    }

    /** {@inheritdoc} */
    protected function doGetNativeExpression()
    {
        $operands = array_map(function(Expression $operand) {
            return $operand->getNativeExpression();
        }, $this->operands);
        $operands = array_unique($operands);

        if (1 == count($operands)) {
            return $operands[0];
        } else {
            return sprintf('(%s)', implode(' || ', $operands));
        }
    }
}
