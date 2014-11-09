<?php

namespace Superruzafa\Rules\Expression\Operator\Logical;

use Superruzafa\Rules\Context;
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
        $min = $max = 1;
    }

    /** {@inheritdoc} */
    protected function doEvaluate(Context $context = null)
    {
        return !((bool)$this->operands[0]->evaluate($context));
    }

    /** {@inheritdoc} */
    protected function doGetNativeExpression()
    {
        return sprintf('(!%s)', $this->operands[0]->getNativeExpression());
    }
}
