<?php

namespace Superruzafa\Rules\Loader\Xml\OperatorParser\Comparison;

use Superruzafa\Rules\Expression\Operator\Comparison\NotEqualTo;
use Superruzafa\Rules\Loader\Xml\OperatorParser;

class NotEqualToParser extends OperatorParser
{
    /** {@inheritdoc} */
    public function parse(\DOMElement $operatorElement, \DOMXPath $xpath)
    {
        return $this->parseOperands(new NotEqualTo(), $operatorElement, $xpath);
    }

    /** {@inheritdoc} */
    public function getElementName()
    {
        return 'notEqualTo';
    }
}
