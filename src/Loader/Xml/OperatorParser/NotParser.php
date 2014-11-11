<?php

namespace Superruzafa\Rules\Loader\Xml\OperatorParser;

use Superruzafa\Rules\Expression\Operator\Logical\NotOp;
use Superruzafa\Rules\Loader\Xml\OperatorParser;

class NotParser extends OperatorParser
{
    /** {@inheritdoc} */
    public function parse(\DOMElement $operatorElement, \DOMXPath $xpath)
    {
        return $this->parseOperands(new NotOp, $operatorElement, $xpath);
    }

    /** {@inheritdoc} */
    public function getElementName()
    {
        return 'not';
    }
}
