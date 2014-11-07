<?php

namespace Superruzafa\Rules\Action;

use Superruzafa\Rules\Action;
use Superruzafa\Rules\Context;

class RunCallback implements Action
{
    /** @var callable */
    private $callback;

    /**
     * Creates a new RunCallback action
     *
     * @param $callback
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Argument is not callable');
        }
        $this->callback = $callback;
    }

    /** {@inheritdoc} */
    public function perform(Context $context)
    {
        return call_user_func($this->callback, $context);
    }
}
