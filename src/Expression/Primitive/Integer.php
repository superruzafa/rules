<?php

namespace Superruzafa\Rules\Expression\Primitive;

use Superruzafa\Rules\Expression\Primitive;

class Integer extends Primitive
{
    /** {@inheritdoc} */
    protected function doSetValue($value)
    {
        if (!is_object($value)) {
            $this->value = intval($value);
        } elseif (method_exists($value, '__toString')) {
            $this->value = intval($value->__toString());
        } else {
            $this->value = 0;
        }
    }
}
