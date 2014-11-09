<?php

namespace Superruzafa\Rules\Action;

use Superruzafa\Rules\Action;
use Superruzafa\Rules\Context;

class Sequence implements Action
{
    /** @var Action[] */
    private $actions;

    /**
     * Creates a new Sequence
     *
     * @param Action $action... Variable number of actions
     */
    public function __construct()
    {
        $actions = func_get_args();
        array_walk($actions, function($action) {
            if (!($action instanceof Action)) {
                throw new \InvalidArgumentException('Invalid action');
            }
        });
        $this->actions = $actions;
    }

    /**
     * Appends an action to the sequence of actions
     *
     * @param Action $action
     * @return Sequence
     */
    public function appendAction(Action $action)
    {
        $this->actions[] = $action;
        return $this;
    }

    /** {@inheritdoc} */
    public function perform(Context $context)
    {
        foreach ($this->actions as $action)
        {
            $action->perform($context);
        }
    }
}
