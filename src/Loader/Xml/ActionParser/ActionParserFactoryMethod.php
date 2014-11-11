<?php

namespace Superruzafa\Rules\Loader\Xml\ActionParser;

use Superruzafa\Rules\Loader\Xml\ActionParser;

class ActionParserFactoryMethod
{
    /** @var ActionParser[] */
    private $pool = array();

    public function create(\DOMElement $actionElement)
    {
        $type = $actionElement->getAttribute('type');
        if (isset($this->pool[$type])) {
            return $this->pool[$type];
        }

        switch ($type) {
            case 'filter-context':
                $parser = new FilterContextParser();
                break;
            default:
                $parser = new OverrideContextParser();
                break;
        }

        return $this->pool[$type] = $parser;
    }
}
