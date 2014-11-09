<?php

namespace Superruzafa\Rules;

use Superruzafa\Rules\Action\NoAction;
use Superruzafa\Rules\Expression\Primitive\Boolean;

class Rule
{
    /** @var string */
    const BEFORE_SUBRULES   = 'before-subrules';

    /** @var string */
    const AFTER_SUBRULES    = 'after-subrules';

    /** @var string */
    const AFTER_RULE        = 'after-rule';

    /** @var string */
    const BEFORE_RULE       = 'before-rule';

    /** @var Expression */
    private $condition;

    /** @var Action[] */
    private $actionHooks;

    /** @var Action */
    private $noAction;

    /** @var Rule[] */
    private $rules = array();

    public function appendRule(Rule $rule, $stage = self::AFTER_SUBRULES)
    {
        $this->rules[$stage] = $rule;
        return $this;
    }

    /**
     * Creates a new Rule
     *
     * @param Expression $condition
     * @param Action $action Performed action after subrules execution
     */
    public function __construct(Expression $condition = null, Action $action = null)
    {
        $this->noAction = new NoAction();
        $this->condition = $condition ?: Boolean::true();
        $this->actionHooks = array(
            self::BEFORE_RULE       => $this->noAction,
            self::BEFORE_SUBRULES   => $this->noAction,
            self::AFTER_SUBRULES    => $action ?: $this->noAction,
            self::AFTER_RULE        => $this->noAction,
        );
    }

    /**
     * @return Action
     */
    public function getAction($stage = self::AFTER_SUBRULES)
    {
        return isset($this->actionHooks[$stage])
            ? $this->actionHooks[$stage]
            : $this->noAction;
    }

    /**
     * @param Action $action
     * @param string $stage
     * @return Rule
     */
    public function setAction(Action $action, $stage = self::AFTER_SUBRULES)
    {
        if (array_key_exists($stage, $this->actionHooks)) {
            $this->actionHooks[$stage] = $action;
        }
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

        $this->actionHooks[self::BEFORE_RULE]->perform($context);

        if (false !== $this->condition->evaluate($context)) {

            $this->actionHooks[self::BEFORE_SUBRULES]->perform($context);

            $callback = function(Context $context, Rule $rule) {
                return $rule->execute($context);
            };
            array_reduce($this->rules, $callback, $context);

            $this->actionHooks[self::AFTER_SUBRULES]->perform($context);
        }

        $this->actionHooks[self::AFTER_RULE]->perform($context);

        return $context;
    }
}
