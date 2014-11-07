<?php

namespace Superruzafa\Rules\Expression;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Superruzafa\Rules\Context;
use Superruzafa\Rules\Expression;
use Superruzafa\Rules\Expression\Primitive\Boolean;
use Superruzafa\Rules\Expression\Primitive\Float;
use Superruzafa\Rules\Expression\Primitive\Integer;
use Superruzafa\Rules\Expression\Primitive\String;

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
     * Creates a new Primitive given a primitive type
     *
     * @param boolean|float|integer|string $primitive
     * @return Primitive
     */
    public static function create($primitive)
    {
        if (is_string($primitive)) {
            return new String($primitive);
        } elseif (is_int($primitive)) {
            return new Integer($primitive);
        } elseif (is_bool($primitive)) {
            return new Boolean($primitive);
        } elseif (is_float($primitive)) {
            return new Float($primitive);
        }
        throw new InvalidArgumentException('Invalid primitive type');
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
