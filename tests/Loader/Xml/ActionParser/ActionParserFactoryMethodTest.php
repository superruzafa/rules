<?php

namespace Superruzafa\Rules\Loader\Xml\ActionParser;

class ActionParserFactoryMethodTest extends \PHPUnit_Framework_TestCase
{
    public function builtInActionParserProvider()
    {
        return array(
            array('filter-context',      'Superruzafa\\Rules\\Loader\\Xml\\ActionParser\\FilterContextParser'),
            array('interpolate-context', 'Superruzafa\\Rules\\Loader\\Xml\\ActionParser\\InterpolateContextParser'),
            array('override-context',    'Superruzafa\\Rules\\Loader\\Xml\\ActionParser\\OverrideContextParser'),
        );
    }

    /**
     * @test
     * @dataProvider builtInActionParserProvider
     * @param string $elementName
     * @param string $expectedActionParserClass
     */
    public function builtInActionParsersAreRegistered($elementName, $expectedActionParserClass)
    {
        $action = ActionParserFactoryMethod::create($elementName);
        $this->assertInstanceOf($expectedActionParserClass, $action);
    }

    /** @test */
    public function unregisteredActionParser()
    {
        $this->setExpectedException('RuntimeException');
        ActionParserFactoryMethod::create('unregistered');
    }

    /** @test */
    public function registeredActionParser()
    {
        $actionParserMock = $this
            ->getMockBuilder('Superruzafa\\Rules\\Loader\\Xml\\ActionParser')
            ->setMethods(array('getTypeName'))
            ->getMockForAbstractClass();
        $actionParserMock
            ->expects($this->exactly(2))
            ->method('getTypeName')
            ->will($this->returnValue('JARL'));
        ActionParserFactoryMethod::registerParser($actionParserMock);
        $this->assertSame($actionParserMock, ActionParserFactoryMethod::create('JARL'));
        ActionParserFactoryMethod::unregisterParser($actionParserMock);
    }
}
