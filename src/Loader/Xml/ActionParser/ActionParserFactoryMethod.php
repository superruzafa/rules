<?php

namespace Superruzafa\Rules\Loader\Xml\ActionParser;

use RuntimeException;
use Superruzafa\Rules\Loader\Xml\ActionParser;

class ActionParserFactoryMethod
{
    /** @var ActionParser[] */
    private static $pool = array();

    /**
     * Registers the built-in ActionParser's
     */
    private static function registerBuiltInParsers()
    {
        if (empty(self::$pool['built-in'])) {
            self::doRegisterParser('built-in', new FilterContextParser);
            self::doRegisterParser('built-in', new OverrideContextParser);
            self::doRegisterParser('built-in', new InterpolateContextParser);
        }
    }

    /**
     * Registers an user defined ActionParser
     *
     * @param ActionParser $actionParser
     */
    public static function registerParser(ActionParser $actionParser)
    {
        self::doRegisterParser('user', $actionParser);
    }

    /**
     * Do the real register of an ActionParser
     *
     * @param string $category
     * @param ActionParser $actionParser
     */
    private static function doRegisterParser($category, ActionParser $actionParser)
    {
        self::$pool[$category][$actionParser->getTypeName()] = $actionParser;
    }

    /**
     * Unregisters an user defined ActionParser
     *
     * @param ActionParser $actionParser
     */
    public static function unregisterParser(ActionParser $actionParser)
    {
        unset(self::$pool['user'][$actionParser->getTypeName()]);
    }

    /**
     * Creates an ActionParser given its name
     *
     * @param string $actionName
     * @return ActionParser
     * @throws RuntimeException
     */
    public static function create($actionName)
    {
        self::registerBuiltInParsers();

        if (isset(self::$pool['user'][$actionName])) {
            return self::$pool['user'][$actionName];
        } elseif (isset(self::$pool['built-in'][$actionName])) {
            return self::$pool['built-in'][$actionName];
        }

        throw new RuntimeException(sprintf('Unknown action parser named "%s"', $actionName));
    }
}
