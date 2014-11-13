<?php

namespace Superruzafa\Rules\Loader\Xml\OperatorParser\Logical;

use Superruzafa\Rules\Expression\Operator\Logical\AndOp;
use Superruzafa\Rules\Loader\Xml\OperatorParser;

class AndParser extends OperatorParser
{
    /** {@inheritdoc} */
    public function parse(\DOMElement $operatorElement, \DOMXPath $xpath)
    {
        return $this->parseOperands(new AndOp, $operatorElement, $xpath);
    }

    /** {@inheritdoc} */
    public function getElementName()
    {
        return 'and';
    }
}
