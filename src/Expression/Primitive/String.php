<?php

namespace Superruzafa\Rules\Expression\Primitive;

use Superruzafa\Rules\Expression\Primitive;

class String extends Primitive
{
    /** {@inheritdoc} */
    protected function doSetValue($value)
    {
        if (is_object($value) && !method_exists($value, '__toString')) {
            $this->value = get_class($value);
        } else {
            $this->value = strval($value);
        }
    }
}
