<?php

namespace Superruzafa\Rules\Action;

use Superruzafa\Rules\Action;
use Superruzafa\Rules\Context;

class OverrideContext implements Action
{
    /** @var Context */
    private $overrideContext;

    /**
     * Creates a new OverrideContext object
     *
     * @param Context $overrideContext
     */
    public function __construct(Context $overrideContext = null)
    {
        $this->overrideContext = $overrideContext ?: new Context();
    }

    /** {@inheritdoc} */
    public function perform(Context $context)
    {
        return $context->override($this->overrideContext);
    }
}
