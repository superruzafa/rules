<?php

namespace Superruzafa\Rules;

use Superruzafa\Rules\Action\NoAction;
use Superruzafa\Rules\Expression\Primitive\Boolean;

class Rule
{
    /** @var Expression */
    private $condition;

    /** @var Action */
    private $action;

    /** @var Rule[] */
    private $rules = array();

    public function appendRule(Rule $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * Creates a new Rule
     *
     * @param Expression $condition
     * @param Action $action
     */
    public function __construct(Expression $condition = null, Action $action = null)
    {
        $this->condition = $condition ?: Boolean::true();
        $this->action = $action ?: new NoAction();
    }

    /**
     * @return Action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param Action $action
     * @return Rule
     */
    public function setAction(Action $action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return Expression
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param Expression $condition
     * @return Rule
     */
    public function setCondition(Expression $condition)
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * Execute this rule
     *
     * @param Context $context
     * @return Context
     */
    public function execute(Context $context = null)
    {
        $context = $context ?: new Context();

        if (false !== $this->condition->evaluate($context)) {
            $this->action->perform($context);
            $callback = function(Context $context, Rule $rule) {
                return $rule->execute($context);
            };
            array_reduce($this->rules, $callback, $context);
        }

        return $context;
    }
}
