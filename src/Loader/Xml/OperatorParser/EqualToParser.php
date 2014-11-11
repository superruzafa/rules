<?php

namespace Superruzafa\Rules\Loader\Xml\OperatorParser;

use Superruzafa\Rules\Expression\Operator\Comparison\EqualTo;
use Superruzafa\Rules\Loader\Xml\OperatorParser;

class EqualToParser extends OperatorParser
{
    /** {@inheritdoc} */
    public function getElementName()
    {
        return 'equalTo';
    }

    /** {@inheritdoc} */
    public function parse(\DOMElement $operatorElement, \DOMXPath $xpath)
    {
        return $this->parseOperands(new EqualTo, $operatorElement, $xpath);
    }
}
