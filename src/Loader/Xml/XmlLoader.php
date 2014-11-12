<?php

namespace Superruzafa\Rules\Loader\Xml;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Superruzafa\Rules\Expression;
use Superruzafa\Rules\Expression\Operator\Logical\AndOp;
use Superruzafa\Rules\Expression\Primitive\Boolean;
use Superruzafa\Rules\Loader\Xml\ActionParser\ActionParserFactoryMethod;
use Superruzafa\Rules\Loader\Xml\OperatorParser\OperatorParserFactoryMethod;
use Superruzafa\Rules\Rule;
use Symfony\Component\Yaml\Exception\RuntimeException;

class XmlLoader
{
    const XMLNS_LOADER = 'https://github.com/superruzafa/rules/schemas/loader';

    /** @var OperatorParserFactoryMethod */
    private $operatorParserFactoryMethod;

    /** @var ActionParserFactoryMethod */
    private $actionParserFactoryMethod;

    public function __construct(DOMDocument $doc)
    {
        $this->xpath = new DOMXPath($doc);
        $this->xpath->registerNamespace('r', self::XMLNS_LOADER);
        $this->operatorParserFactoryMethod = new OperatorParserFactoryMethod();
        $this->actionParserFactoryMethod = new ActionParserFactoryMethod();
    }

    public function load()
    {
        $nodes = $this->xpath->query('/r:rule');
        if (0 == $nodes->length) {
            throw new RuntimeException('XML contains no rule definition');
        }
        return $this->parseRuleElement($nodes->item(0));
    }

    private function parseRuleElement(DOMElement $ruleElement)
    {
        $rule = new Rule();
        $rule->setName($ruleElement->getAttribute('name'));

        $rule->setCondition($this->parseCondition($ruleElement));

        $actionElements = $this->xpath->query('r:action', $ruleElement);
        foreach ($actionElements as $actionElement) {
            $this->parseActionElement($actionElement, $rule);
        }

        $subruleElements = $this->xpath->query('r:rule', $ruleElement);
        foreach ($subruleElements as $ruleElement) {
            $rule->appendRule($this->parseRuleElement($ruleElement));
        }

        return $rule;
    }

    /**
     * @param DOMElement $ruleElement
     * @return Expression
     */
    private function parseCondition(DOMElement $ruleElement)
    {
        $conditionElements = $this->xpath->query('r:condition', $ruleElement);
        $conditions = array();
        foreach ($conditionElements as $conditionElement) {
            $conditions[] = $this->parseConditionElement($conditionElement);
        }
        switch (count($conditions)) {
            case 0:
                return Boolean::true();
            case 1:
                return $conditions[0];
            default:
                $and = new AndOp();
                array_map(function ($condition) use ($and) {
                    $and->addOperand($condition);
                }, $conditions);
                return $and;
        }
    }

    private function parseConditionElement(DOMElement $conditionElement)
    {
        $operatorElements = $this->xpath->query(sprintf('*[namespace-uri() = "%s"]', self::XMLNS_LOADER), $conditionElement);
        $operators = array();
        foreach ($operatorElements as $operatorElement) {
            $operatorParser = $this->operatorParserFactoryMethod->create($operatorElement);
            $operators[] = $operatorParser->parse($operatorElement, $this->xpath);
        }
        switch (count($operators)) {
            case 0:
                return Boolean::true();
            case 1:
                return $operators[0];
            default:
                $and = new AndOp();
                array_map(function ($operator) use ($and) {
                    $and->addOperand($operator);
                }, $operators);
                return $and;
        }
    }

    private function parseActionElement(DOMElement $actionElement, Rule $rule)
    {
        $actionName = $actionElement->getAttribute('type') ?: 'override-context';
        $actionParser = $this->actionParserFactoryMethod->create($actionName);
        $actionParser->parse($actionElement, $rule, $this->xpath);
    }
}
