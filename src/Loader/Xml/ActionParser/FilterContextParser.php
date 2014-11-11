<?php

namespace Superruzafa\Rules\Loader\Xml\ActionParser;

use Superruzafa\Rules\Action\FilterContext;
use Superruzafa\Rules\Loader\Xml\ActionParser;
use Superruzafa\Rules\Rule;

class FilterContextParser implements ActionParser
{
    /** {@inheritdoc} */
    public function parse(\DOMElement $actionElement, Rule $rule, \DOMXPath $xpath)
    {
        $action = new FilterContext();
        if ($actionElement->hasAttribute('allow-keys')) {
            $keys = $actionElement->getAttribute('allow-keys');
            $action->setMode(FilterContext::ALLOW_KEYS);
        } else {
            $keys = $actionElement->getAttribute('disallow-keys');
            $action->setMode(FilterContext::DISALLOW_KEYS);
        }
        $action->setKeys(array_filter(preg_split('/\s+/', $keys)));
        $rule->setAction($action, $actionElement->getAttribute('stage'));
        return $action;
    }
}

