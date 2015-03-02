<?php

namespace Superruzafa\Rules;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Superruzafa\Template\ArrayTemplate;

class Context implements ArrayAccess, Countable, IteratorAggregate
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

    public function interpolate(Context $context = null)
    {
        $context = $context ? $context->context : $this->context;
        $template = new ArrayTemplate($this->context);
        $this->context = $template->render($context);
        return $this;
    }

    /** {@inheritdoc} */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->context);
    }

    /** {@inheritdoc} */
    public function offsetGet($offset)
    {
	return isset($this->context[$offset]) ? $this->context[$offset] : null;
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

    /** {@inheritdoc} */
    public function getIterator()
    {
        return new \ArrayObject($this->context);
    }
}
