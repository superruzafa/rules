<?php

namespace Superruzafa\Rules;

use ArrayAccess;
use Countable;

class Context implements ArrayAccess, Countable
{
    /** @var array */
    private $context;

    /**
     * Creates a new Context
     *
     * @param array $array
     */
    public function __construct(array $array = null)
    {
        $this->context = $array ?: array();
    }

    /**
     * Overrides the entries from this context with the ones for another one.
     *
     * @param Context $context
     * @return Context
     */
    public function override(Context $context)
    {
        $this->context = array_merge($this->context, $context->context);
        return $this;
    }

    /** {@inheritdoc} */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->context);
    }

    /** {@inheritdoc} */
    public function &offsetGet($offset)
    {
        return $this->context[$offset];
    }

    /** {@inheritdoc} */
    public function offsetSet($offset, $value)
    {
        $this->context[$offset] = $value;
    }

    /** {@inheritdoc} */
    public function offsetUnset($offset)
    {
        unset($this->context[$offset]);
    }

    /** {@inheritdoc} */
    public function count()
    {
        return count($this->context);
    }
}
