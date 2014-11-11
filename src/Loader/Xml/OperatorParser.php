<?php

namespace Superruzafa\Rules\Loader\Xml;

use Superruzafa\Rules\Expression\Primitive;

abstract class OperatorParser
{
    abstract public function parse(\DOMElement $operatorElement, \DOMXPath $xpath);

    protected function parseOperands(\DOMElement $operatorElement, \DOMXPath $xpath)
    {
        $operands = array();
        foreach ($operatorElement->attributes as $attribute) {
            $operands[] = Primitive::create($attribute->nodeValue);
        }
        return $operands;
    }
}
