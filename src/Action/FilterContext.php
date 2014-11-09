<?php

namespace Superruzafa\Rules\Action;

use Superruzafa\Rules\Action;
use Superruzafa\Rules\Context;

class FilterContext implements Action
{
    /** @var string */
    const ALLOW_KEYS = 'allow';

    /** @var string */
    const DISALLOW_KEYS = 'disallow';

    /** @var string */
    private $mode = self::ALLOW_KEYS;

    /** @var string[] */
    private $keys;

    /**
     * Creates a new FilterContext
     *
     * @param string $mode
     * @param string $key... Filter keys
     */
    public function __construct($mode = self::ALLOW_KEYS)
    {
        $args = func_get_args();
        $this->setMode(array_shift($args));
        $this->keys = $args;
    }

    /**
     * Gets the filtering mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Sets the filtering mode
     *
     * @param string $mode
     * @return FilterContext
     */
    public function setMode($mode)
    {
        $this->mode = ($mode == self::DISALLOW_KEYS)
            ? self::DISALLOW_KEYS
            : self::ALLOW_KEYS;
        return $this;
    }

    /**
     * Gets the filtering keys
     *
     * @return string[]
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Sets the filtering keys
     *
     * @param string[]|array $key...
     * @return FilterContext
     */
    public function setKeys($key)
    {
        if (is_array($key)) {
            $this->keys = $key;
        } else {
            $this->keys = func_get_args();
        }
        return $this;
    }

    /** {@inheritdoc} */
    public function perform(Context $context)
    {
        if ($this->mode == self::DISALLOW_KEYS) {
            $this->performDisallowKeys($context);
        } else {
            $this->performAllowKeys($context);
        }
    }

    /**
     * Keeps those context keys included in the keys list
     *
     * @param Context $context
     */
    private function performAllowKeys(Context $context)
    {
        foreach ($context as $key => $value) {
            if (!in_array($key, $this->keys)) {
                unset($context[$key]);
            }
        }
    }

    /**
     * Discards those context keys included in the keys list
     *
     * @param Context $context
     */
    private function performDisallowKeys(Context $context)
    {
        foreach ($context as $key => $value) {
            if (in_array($key, $this->keys)) {
                unset($context[$key]);
            }
        }
    }
}
