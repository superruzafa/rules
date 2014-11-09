<?php

namespace Superruzafa\Rules\Expression\Operator\Comparison;

use Superruzafa\Rules\Context;
use Superruzafa\Rules\Expression;
use Superruzafa\Rules\Expression\Operator;

class NotEqualTo extends Operator
{
    /** {@inheritdoc} */
    public function getName()
    {
        return 'notEqualTo';
    }

    /** {@inheritdoc} */
    protected function defineOperandsCount(&$min = 0, &$max = null)
    {
        $min = 2;
    }

    /** {@inheritdoc} */
    protected function doEvaluate(Context $context = null)
    {
        return $this->binaryReduction(
            $context,
            function ($value1, $value2) {
                return $value1 != $value2;
            }
        );
    }

    /** {@inheritdoc} */
    protected function doGetNativeExpression()
    {
        $operands = array_map(function (Expression $operand) {
            return $operand->getNativeExpression();
        }, $this->operands);

        $operands = array_values(array_unique($operands));

        switch (count($operands)) {
            case 1:
                return 'false';
            case 2:
                return sprintf('(%s != %s)', $operands[0], $operands[1]);
            default:
                $i = 0;
                $limit = count($operands) - 1;
                $subEqualTo = array();
                while ($i < $limit) {
                    $subEqualTo[] = sprintf('(%s != %s)', $operands[$i], $operands[$i + 1]);
                    ++$i;
                }
                return sprintf('(%s)', implode(' && ', $subEqualTo));
        }
    }
}
