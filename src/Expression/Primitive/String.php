<?php

namespace Superruzafa\Rules\Expression\Primitive;

use Superruzafa\Rules\Context;
use Superruzafa\Rules\Expression\Primitive;

class String extends Primitive
{
    /** {@inheritdoc} */
    public function evaluate(Context $context = null)
    {
        $pattern = '/\{\{\s*((?:(?!}})\S)+)\s*}}/';
        $callback = function($match) use ($context) {
            list(, $key) = $match;
            return isset($context[$key]) ? $context[$key] : '';
        };
        return preg_replace_callback($pattern, $callback, $this->value);
    }

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
