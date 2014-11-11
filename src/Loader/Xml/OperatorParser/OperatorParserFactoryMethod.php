<?php

namespace Superruzafa\Rules\Loader\Xml\OperatorParser;

use RuntimeException;
use Superruzafa\Rules\Loader\Xml\OperatorParser;
use Superruzafa\Rules\Loader\Xml\OperatorParser\Comparison\EqualToParser;
use Superruzafa\Rules\Loader\Xml\OperatorParser\Comparison\NotEqualToParser;
use Superruzafa\Rules\Loader\Xml\OperatorParser\Logical\AndParser;
use Superruzafa\Rules\Loader\Xml\OperatorParser\Logical\NotParser;
use Superruzafa\Rules\Loader\Xml\OperatorParser\Logical\OrParser;

class OperatorParserFactoryMethod
{
    /** @var OperatorParser[] */
    private static $pool = array();

    /**
     * Registers the built-in OperatorParser's
     */
    private static function registerBuiltInParsers()
    {
        if (empty(self::$pool)) {
            self::registerParser(new AndParser);
            self::registerParser(new OrParser);
            self::registerParser(new NotParser);
            self::registerParser(new EqualToParser);
            self::registerParser(new NotEqualToParser);
        }
    }

    /**
     * Registers an user defined OperatorParser
     *
     * @param OperatorParser $operatorParser
     */
    public static function registerParser(OperatorParser $operatorParser)
    {
        self::$pool[$operatorParser->getElementName()] = $operatorParser;
    }

    /**
     * Creates a parser given an XML element
     *
     * @param \DOMElement $operatorElement
     * @return OperatorParser
     */
    public static function create(\DOMElement $operatorElement)
    {
        self::registerBuiltInParsers();

        $operatorName = $operatorElement->localName;

        if (!isset(self::$pool[$operatorName])) {
            throw new RuntimeException(sprintf('Unknown operator parser named "%s"', $operatorName));
        }
        return self::$pool[$operatorName];

    }
}
