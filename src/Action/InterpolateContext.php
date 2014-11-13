<?php

namespace Superruzafa\Rules\Action;

use Superruzafa\Rules\Action;
use Superruzafa\Rules\Context;

class InterpolateContext implements Action
{
    /** {@inheritdoc} */
    public function perform(Context $context)
    {
        $context->interpolate();
    }
}
