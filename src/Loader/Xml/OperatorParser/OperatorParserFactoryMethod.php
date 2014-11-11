<?php

namespace Superruzafa\Rules\Loader\Xml\OperatorParser;

use RuntimeException;
use Superruzafa\Rules\Loader\Xml\OperatorParser;

class OperatorParserFactoryMethod
{
    /** @var OperatorParser[] */
    private $pool = array();

    public function create(\DOMElement $actionElement)
    {
        $operatorName = $actionElement->localName;
        if (isset($this->pool[$operatorName])) {
            return $this->pool[$operatorName];
        }

        switch ($operatorName) {
            case 'equalTo':
                $parser = new EqualToParser();
                break;
            default:
                throw new RuntimeException(sprintf('Unknown operator parser named "%s"', $operatorName));
        }

        return $this->pool[$operatorName] = $parser;
    }
}
