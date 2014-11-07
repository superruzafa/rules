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

    /**
     * Creates a true Boolean expression
     *
     * @return \Superruzafa\Rules\Expression\Primitive\Boolean
     */
    public static function true()
    {
        return new Boolean(true);
    }

    /**
     * Creates a false Boolean expression
     *
     * @return \Superruzafa\Rules\Expression\Primitive\Boolean
     */
    public static function false()
    {
        return new Boolean(false);
    }
}
