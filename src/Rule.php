<?php

namespace Superruzafa\Rules;

use Superruzafa\Rules\Action\NoAction;
use Superruzafa\Rules\Action\Sequence;
use Superruzafa\Rules\Expression\Primitive\Boolean;

class Rule
{
    /** @var string */
    const BEFORE_RULE = 'before-rule';

    /** @var string */
    const BEFORE_SUBRULES   = 'before-subrules';

    /** @var string */
    const AFTER_SUBRULES    = 'after-subrules';

    /** @var string */
    const AFTER_RULE        = 'after-rule';

    /** @var string */
    private $name = '';

    /** @var Expression */
    private $condition;

    /** @var Action[] */
    private $actions;

    /** @var Action */
    private $noAction;

    /** @var Rule[] */
    private $rules = array();

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
        $this->actions = array(
            self::BEFORE_RULE => null,
            self::BEFORE_SUBRULES => null,
            self::AFTER_SUBRULES => $action,
            self::AFTER_RULE => null,
        );
    }

    /**
     * Gets the rule's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the rule's name
     *
     * @param string $name
     * @return Rule
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Action
     */
    public function getAction($stage = self::AFTER_SUBRULES)
    {
        return is_null($this->actions[$stage])
            ? $this->noAction
            : $this->actions[$stage];
    }

    /**
     * @param Action $action
     * @param string $stage
     * @return Rule
     */
    public function setAction(Action $action, $stage = self::AFTER_SUBRULES)
    {
        if (!array_key_exists($stage, $this->actions)) {
            $stage = self::AFTER_SUBRULES;
        }
        $this->actions[$stage] = $action;
        return $this;
    }

    public function appendAction(Action $action, $stage = self::AFTER_SUBRULES)
    {
        if (!array_key_exists($stage, $this->actions)) {
            $stage = self::AFTER_SUBRULES;
        }

        $stageAction = &$this->actions[$stage];
        if ($stageAction instanceof Sequence) {
            $stageAction->appendAction($action);
        } elseif (is_null($stageAction)) {
            $stageAction = $action;
        } else {
            $sequence = new Sequence($stageAction, $action);
            $this->actions[$stage] = $sequence;
        }
        unset($stageAction);
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
     * Appends a subrule to this rule
     *
     * @param Rule $rule
     * @return Rule
     */
    public function appendRule(Rule $rule)
    {
        $this->rules[] = $rule;
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

        $this->getAction(self::BEFORE_RULE)->perform($context);

        if (false !== $this->condition->evaluate($context)) {

            $this->getAction(self::BEFORE_SUBRULES)->perform($context);

            $callback = function(Context $context, Rule $rule) {
                return $rule->execute($context);
            };
            array_reduce($this->rules, $callback, $context);

            $this->getAction(self::AFTER_SUBRULES)->perform($context);
        }

        $this->getAction(self::AFTER_RULE)->perform($context);

        return $context;
    }
}
