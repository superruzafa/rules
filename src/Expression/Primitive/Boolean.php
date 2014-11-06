<?php

namespace Superruzafa\Rules\Expression\Primitive;

use Superruzafa\Rules\Expression\Primitive;

class Boolean extends Primitive
{
    /** {@inheritdoc} */
    protected function doSetValue($value)
    {
        $this->value = (bool)$value;
    }
}
