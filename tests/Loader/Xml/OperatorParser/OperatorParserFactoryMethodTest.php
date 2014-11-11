<?php

namespace Loader\Xml\OperatorParser;

use Superruzafa\Rules\Loader\Xml\OperatorParser\OperatorParserFactoryMethod;
use Superruzafa\Rules\Loader\Xml\XmlLoader;

class OperatorParserFactoryMethodTest extends \PHPUnit_Framework_TestCase
{
    public function builtInOperatorParserProvider()
    {
        return array(
            array('and', 'Superruzafa\\Rules\\Loader\\Xml\\OperatorParser\\Logical\\AndParser'),
            array('or', 'Superruzafa\\Rules\\Loader\\Xml\\OperatorParser\\Logical\\OrParser'),
            array('not', 'Superruzafa\\Rules\\Loader\\Xml\\OperatorParser\\Logical\\NotParser'),
            array('equalTo', 'Superruzafa\\Rules\\Loader\\Xml\\OperatorParser\\Comparison\\EqualToParser'),
            array('notEqualTo', 'Superruzafa\\Rules\\Loader\\Xml\\OperatorParser\\Comparison\\NotEqualToParser'),
        );
    }

    /**
     * @test
     * @dataProvider builtInOperatorParserProvider
     * @param string $elementName
     * @param string $expectedOperatorParserClass
     */
    public function builtInOperatorParsersAreRegistered($elementName, $expectedOperatorParserClass)
    {
        $operator = OperatorParserFactoryMethod::create($this->createElement($elementName));
        $this->assertInstanceOf($expectedOperatorParserClass, $operator);
    }

    /** @test */
    public function unregisteredOperatorParser()
    {
        $this->setExpectedException('RuntimeException');
        OperatorParserFactoryMethod::create($this->createElement('unregistered'));
    }

    /** @test */
    public function registeredOperatorParser()
    {
        $operatorParserMock = $this
            ->getMockBuilder('Superruzafa\\Rules\\Loader\\Xml\\OperatorParser')
            ->setMethods(array('getElementName'))
            ->getMockForAbstractClass();
        $operatorParserMock
            ->expects($this->once())
            ->method('getElementName')
            ->will($this->returnValue('JARL'));
        OperatorParserFactoryMethod::registerParser($operatorParserMock);
        $this->assertSame($operatorParserMock, OperatorParserFactoryMethod::create($this->createElement('JARL')));
    }

    /**
     * Creates an namespaced element
     *
     * @param string $name
     * @return \DOMElement
     */
    private function createElement($name)
    {
        $doc = new \DOMDocument();
        return $doc->createElementNS(XmlLoader::XMLNS_LOADER, $name);
    }
}
