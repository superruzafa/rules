<?php

namespace Superruzafa\Rules;

interface Expression
{
    /**
     * Evaluates an expression
     *
     * @return mixed
     */
    public function evaluate();

    /**
     * Returns an equivalent expression in PHP code
     *
     * @return string
     */
    public function getNativeExpression();
}
