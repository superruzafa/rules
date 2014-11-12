<?php

namespace Superruzafa\Rules\Loader\Xml\ActionParser;

use RuntimeException;
use Superruzafa\Rules\Loader\Xml\ActionParser;

class ActionParserFactoryMethod
{
    /** @var ActionParser[] */
    private static $pool = array();

    private static function registerBuiltInParsers()
    {
        if (empty(self::$pool)) {
            self::registerParser(new FilterContextParser);
            self::registerParser(new OverrideContextParser);
            self::registerParser(new InterpolateContextParser);
        }
    }

    private static function registerParser(ActionParser $actionParser)
    {
        self::$pool[$actionParser->getTypeName()] = $actionParser;
    }

    public function create($actionName)
    {
        self::registerBuiltInParsers();

        if (!isset(self::$pool[$actionName])) {
            throw new RuntimeException(sprintf('Unknown action parser named "%s"', $actionName));
        }
        return self::$pool[$actionName];
    }
}
