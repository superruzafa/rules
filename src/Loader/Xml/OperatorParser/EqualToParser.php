<?php

namespace Superruzafa\Rules\Loader\Xml\OperatorParser;

use Superruzafa\Rules\Expression\Operator\Comparison\EqualTo;
use Superruzafa\Rules\Loader\Xml\OperatorParser;

class EqualToParser extends OperatorParser
{
    public function parse(\DOMElement $operatorElement, \DOMXPath $xpath)
    {
        $operator = new EqualTo();
        $operands = $this->parseOperands($operatorElement, $xpath);
        foreach ($operands as $operand) {
            $operator->addOperand($operand);
        }
        return $operator;
    }
}
