<?php

namespace Superruzafa\Rules\Loader\Xml\ActionParser;

use Superruzafa\Rules\Action\InterpolateContext;
use Superruzafa\Rules\Loader\Xml\ActionParser;
use Superruzafa\Rules\Rule;

class InterpolateContextParser implements ActionParser
{
    public function getTypeName()
    {
        return 'interpolate-context';
    }

    public function parse(\DOMElement $actionElement, Rule $rule, \DOMXPath $xpath)
    {
        $action = new InterpolateContext();
        $rule->appendAction($action, $actionElement->getAttribute('stage'));
        return $action;
    }
}
