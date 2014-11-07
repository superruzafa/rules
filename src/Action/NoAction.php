<?php

namespace Superruzafa\Rules\Action;

use Superruzafa\Rules\Action;
use Superruzafa\Rules\Context;

class NoAction implements Action
{
    /** {@inheritdoc} */
    public function perform(Context $context)
    {
    }
}
