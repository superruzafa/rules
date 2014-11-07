<?php

namespace Superruzafa\Rules;

interface Action
{
    /**
     * Performs an action
     *
     * @param Context $context
     * @return mixed
     */
    public function perform(Context $context);
}
