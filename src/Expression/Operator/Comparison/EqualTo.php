<?php

namespace Superruzafa\Rules\Expression\Operator\Comparison;

use Superruzafa\Rules\Context;
use Superruzafa\Rules\Expression;
use Superruzafa\Rules\Expression\Operator;

class EqualTo extends Operator
{
    /** {@inheritdoc} */
    public function getName()
    {
        return 'equalTo';
    }

    /** {@inheritdoc} */
    protected function defineOperandsCount(&$min = 0, &$max = null)
    {
        $min = 2;
    }

    /** {@inheritdoc} */
    protected function doEvaluate(Context $context = null)
    {
        $i = 1;
        $limit = count($this->operands) - 1;
        $current = $this->operands[1]->evaluate($context);
        $areEqual = $this->operands[0]->evaluate($context) == $current;
        while ($areEqual && $i < $limit) {
            $previous = $current;
            $current = $this->operands[$i]->evaluate($context);
            $areEqual = $previous == $current;
            ++$i;
        }

        return $areEqual;
    }

    /** {@inheritdoc} */
    protected function doGetNativeExpression()
    {
        $operands = array_map(function(Expression $operand) {
            return $operand->getNativeExpression();
        }, $this->operands);

        $operands = array_unique($operands);

        switch (count($operands)) {
            case 1:
                return 'true';
            case 2:
                return sprintf('(%s == %s)', $operands[0], $operands[1]);
            default:
                $i = 0;
                $limit = count($operands) - 1;
                $subEqualTo = array();
                while ($i < $limit) {
                    $subEqualTo[] = sprintf('(%s == %s)', $operands[$i], $operands[$i + 1]);
                    ++$i;
                }
                return sprintf('(%s)', implode(' && ', $subEqualTo));
        }
    }
}
