<?php

namespace Superruzafa\Rules\Expression\Primitive;

use Superruzafa\Rules\Expression\Primitive;

class Float extends Primitive
{
    /** {@inheritdoc} */
    protected function doSetValue($value)
    {
        if (!is_object($value)) {
            $this->value = floatval($value);
        } elseif (method_exists($value, '__toString')) {
            $this->value = floatval($value->__toString());
        } else {
            $this->value = 0.0;
        }
    }
}
