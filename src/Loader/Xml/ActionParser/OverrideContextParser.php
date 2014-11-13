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

        $attributes = $xpath->query('attribute::*[name() = local-name()]', $actionElement);
        foreach ($attributes as $attribute) {
            $context[$attribute->nodeName] = $attribute->textContent;
        }

        $nodes = $xpath->query('child::*[name() = local-name()]', $actionElement);
        foreach ($nodes as $node) {
            $key = $node->nodeName;
            $value = $this->parseNodeValue($node, $xpath);
            if (isset($context[$node->nodeName])) {
                $context[$key] = array_merge_recursive((array)$context[$key], (array)$value);
            } else {
                $context[$key] = $value;
            }
        }

        $action = new OverrideContext($context);
        $rule->appendAction($action, $actionElement->getAttribute('stage'));
        return $action;
    }

    private function parseNodeValue($node, \DOMXPath $xpath)
    {
        if ($node instanceof \DOMAttr) {
            return $node->nodeValue;
        }

        $nodes = $xpath->query('attribute::*[name() = local-name()] | child::*[name() = local-name()]', $node);
        if (0 == $nodes->length) {
            return $node->textContent;
        }

        $nodeValue = array();
        foreach ($nodes as $subnode) {
            $key = $subnode->nodeName;
            $value = $this->parseNodeValue($subnode, $xpath);
            if (isset($nodeValue[$key])) {
                $nodeValue[$key] = array_merge_recursive((array)$nodeValue[$key], (array)$value);
//                $nodeValue[$key][] = $value;
            } else {
                $nodeValue[$key] = $value;
            }
        }
        return $nodeValue;
    }
}

