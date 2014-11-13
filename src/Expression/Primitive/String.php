<?php

namespace Superruzafa\Rules\Expression\Primitive;

use Superruzafa\Rules\Context;
use Superruzafa\Rules\Expression\Primitive;
use Superruzafa\Template\StringTemplate;

class String extends Primitive
{
    /** {@inheritdoc} */
    public function evaluate(Context $context = null)
    {
        $string = new StringTemplate($this->value);
        $variables = $context ? $context->getIterator()->getArrayCopy() : array();
        return $string->render($variables);
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
