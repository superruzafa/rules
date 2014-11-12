<?php

namespace Superruzafa\Rules\Loader\Xml\ActionParser;

use Superruzafa\Rules\Action\OverrideContext;
use Superruzafa\Rules\Context;
use Superruzafa\Rules\Loader\Xml\ActionParser;
use Superruzafa\Rules\Rule;

class OverrideContextParser implements ActionParser
{
    public function getTypeName()
    {
        return 'override-context';
    }

    public function parse(\DOMElement $actionElement, Rule $rule, \DOMXPath $xpath)
    {
        $context = new Context();
        $elements = $xpath->query('*[name() = local-name()]', $actionElement);
        foreach ($elements as $element) {
            $context[$element->nodeName] = $element->textContent;
        }

        $action = new OverrideContext($context);
        $rule->appendAction($action, $actionElement->getAttribute('stage'));
        return $action;
    }
}

