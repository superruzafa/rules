<?php

namespace Superruzafa\Rules\Loader\Xml;

use Superruzafa\Rules\Rule;

interface ActionParser
{
    public function parse(\DOMElement $actionElement, Rule $rule, \DOMXPath $xpath);
}
