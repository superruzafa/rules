<?php

namespace Superruzafa\Rules;

interface Expression
{
    /**
     * Evaluates an expression
     *
     * @param Context $context
     * @return mixed
     */
    public function evaluate(Context $context = null);

    /**
     * Returns an equivalent expression in PHP code
     *
     * @return string
     */
    public function getNativeExpression();
}
