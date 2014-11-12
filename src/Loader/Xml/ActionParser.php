<?php

namespace Superruzafa\Rules\Loader\Xml;

use Superruzafa\Rules\Rule;

interface ActionParser
{
    public function getTypeName();

    public function parse(\DOMElement $actionElement, Rule $rule, \DOMXPath $xpath);
}
