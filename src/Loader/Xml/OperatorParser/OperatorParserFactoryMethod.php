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
        if (empty(self::$pool['built-in'])) {
            self::doRegisterParser('built-it', new AndParser);
            self::doRegisterParser('built-it', new OrParser);
            self::doRegisterParser('built-it', new NotParser);
            self::doRegisterParser('built-it', new EqualToParser);
            self::doRegisterParser('built-it', new NotEqualToParser);
        }
    }

    /**
     * Registers an user defined OperatorParser
     *
     * @param OperatorParser $operatorParser
     */
    public static function registerParser(OperatorParser $operatorParser)
    {
        self::doRegisterParser('user', $operatorParser);
    }

    /**
     * Do the real register of an OperatorParser
     *
     * @param string $category
     * @param OperatorParser $operatorParser
     */
    private static function doRegisterParser($category, OperatorParser $operatorParser)
    {
        self::$pool[$category][$operatorParser->getElementName()] = $operatorParser;
    }

    /**
     * Unregisters an user defined OperatorParser
     *
     * @param OperatorParser $operatorParser
     */
    public static function unregisterParser(OperatorParser $operatorParser)
    {
        unset(self::$pool['user'][$operatorParser->getElementName()]);
    }

    /**
     * Creates an OperatorParser given its name
     *
     * @param string $operatorName
     * @return OperatorParser
     */
    public static function create($operatorName)
    {
        self::registerBuiltInParsers();

        if (isset(self::$pool['user'][$operatorName])) {
            return self::$pool['user'][$operatorName];
        } elseif (isset(self::$pool['built-it'][$operatorName])) {
            return self::$pool['built-it'][$operatorName];
        }
        throw new RuntimeException(sprintf('Unknown operator parser named "%s"', $operatorName));
    }
}
