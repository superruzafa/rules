<?php

namespace Superruzafa\Rules\Expression;

use Superruzafa\Rules\Context;
use Superruzafa\Rules\Expression;

abstract class Primitive implements Expression
{
    /** @var mixed */
    protected $value = null;

    /**
     * Creates a new primitive value
     *
     * @param mixed $value Optional initialization value
     */
    public function __construct($value = null)
    {
        if (!is_null($value)) {
            $this->setValue($value);
        }
    }

    /**
     * Sets the primitive value
     *
     * @param mixed $value
     * @return Primitive
     */
    final public function setValue($value)
    {
        $this->doSetValue($value);
        return $this;
    }

    /**
     * Does the real job for set the primitive's value
     *
     * @param mixed $value
     */
    abstract protected function doSetValue($value);

    /** {@inheritdoc} */
    public function evaluate(Context $context = null)
    {
        return $this->value;
    }

    /** {@inheritdoc} */
    public function getNativeExpression()
    {
        return var_export($this->value, true);
    }
}
