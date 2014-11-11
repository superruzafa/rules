<?php

namespace Superruzafa\Rules\Loader\Xml\OperatorParser;

use Superruzafa\Rules\Expression\Operator\Logical\OrOp;
use Superruzafa\Rules\Loader\Xml\OperatorParser;

class OrParser extends OperatorParser
{
    /** {@inheritdoc} */
    public function parse(\DOMElement $operatorElement, \DOMXPath $xpath)
    {
        return $this->parseOperands(new OrOp, $operatorElement, $xpath);
    }

    /** {@inheritdoc} */
    public function getElementName()
    {
        return 'or';
    }
}
