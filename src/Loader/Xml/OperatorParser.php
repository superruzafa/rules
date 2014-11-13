<?php

namespace Superruzafa\Rules\Loader\Xml;

use Superruzafa\Rules\Expression\Operator;
use Superruzafa\Rules\Expression\Primitive;
use Superruzafa\Rules\Loader\Xml\OperatorParser\OperatorParserFactoryMethod;

abstract class OperatorParser
{
    /**
     * Gets the name used in the XML element to define the operator
     *
     * @return string
     */
    abstract public function getElementName();

    /**
     * Parses an XML element as an operator
     *
     * @param \DOMElement $operatorElement
     * @param \DOMXPath $xpath
     * @return Operator
     */
    abstract public function parse(\DOMElement $operatorElement, \DOMXPath $xpath);

    /**
     * Parses the operands defined in an XML element and adds them to an operator
     *
     * @param Operator $operator
     * @param \DOMElement $operatorElement
     * @param \DOMXPath $xpath
     * @return Operator
     */
    protected function parseOperands(Operator $operator, \DOMElement $operatorElement, \DOMXPath $xpath)
    {
        foreach ($operatorElement->attributes as $attribute) {
            $operator->addOperand(Primitive::create($attribute->nodeValue));
        }

        $nodes = $xpath->query(sprintf('*[namespace-uri() = "%s"]', XmlLoader::XMLNS_LOADER), $operatorElement);
        foreach ($nodes as $node) {
            $parser = OperatorParserFactoryMethod::create($node->localName);
            $operator->addOperand($parser->parse($node, $xpath));
        }
        return $operator;
    }
}
