<?php

namespace Superruzafa\Rules\Loader\Xml;

use Superruzafa\Rules\Loader\Xml\OperatorParser\OperatorParserFactoryMethod;

class OperatorParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var OperatorParser */
    private $operator;

    protected function setUp()
    {
        $this->operator = $this
            ->getMockBuilder('Superruzafa\\Rules\\Loader\\Xml\\OperatorParser')
            ->enableProxyingToOriginalMethods()
            ->getMockForAbstractClass();
    }

    /** @test */
    public function parseAttributeOperands()
    {
        $doc = new \DOMDocument();
        $operatorElement = $doc->createElement('foo');
        $operatorElement->setAttribute('aaa', 'x');
        $operatorElement->setAttribute('bbb', 'x');
        $operatorElement->setAttribute('ccc', 'x');

        $xpath = $this
            ->getMockBuilder('DOMXPath')
            ->disableOriginalConstructor()
            ->setMethods(array('query'))
            ->getMock();
        $xpath
            ->expects($this->once())
            ->method('query')
            ->will($this->returnValue(array()));

        $operator = $this
            ->getMockBuilder('Superruzafa\\Rules\\Expression\\Operator')
            ->setMethods(array('addOperand'))
            ->getMockForAbstractClass();
        $operator
            ->expects($this->exactly(3))
            ->method('addOperand')
            ->with($this->isInstanceOf('Superruzafa\\Rules\\Expression'));

        $class = new \ReflectionObject($this->operator);
        $method = $class->getMethod('parseOperands');
        $method->setAccessible(true);
        $this->assertSame($operator, $method->invoke($this->operator, $operator, $operatorElement, $xpath));
    }

    /** @test */
    public function parseElementOperands()
    {
        $operand = $this->getMockForAbstractClass('Superruzafa\\Rules\\Expression');

        $operatorParser = $this
            ->getMockBuilder('Superruzafa\\Rules\\Loader\\Xml\\OperatorParser')
            ->setMethods(array('getElementName', 'parse'))
            ->getMockForAbstractClass();
        $operatorParser
            ->expects($this->once())
            ->method('getElementName')
            ->will($this->returnValue('operator-element-name'));
        $operatorParser
            ->expects($this->exactly(2))
            ->method('parse')
            ->will($this->returnValue($operand));

        OperatorParserFactoryMethod::registerParser($operatorParser);

        $doc = new \DOMDocument();
        $operatorElement = $doc->createElement('operator-element-name');
        $xpath = $this
            ->getMockBuilder('DOMXPath')
            ->disableOriginalConstructor()
            ->setMethods(array('query'))
            ->getMock();
        $xpath
            ->expects($this->once())
            ->method('query')
            ->will($this->returnValue(array($operatorElement, $operatorElement)));

        $operator = $this
            ->getMockBuilder('Superruzafa\\Rules\\Expression\\Operator')
            ->setMethods(array('addOperand'))
            ->getMockForAbstractClass();
        $operator
            ->expects($this->exactly(2))
            ->method('addOperand')
            ->with($operand);

        $class = new \ReflectionObject($this->operator);
        $method = $class->getMethod('parseOperands');
        $method->setAccessible(true);
        $this->assertSame($operator, $method->invoke($this->operator, $operator, $operatorElement, $xpath));
    }

}
