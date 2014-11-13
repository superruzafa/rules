<?php

namespace Superruzafa\Rules\Loader\Xml\ActionParser;

use Superruzafa\Rules\Context;
use Superruzafa\Rules\Rule;

class OverrideContextParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var OverrideContextParser */
    private $parser;

    protected function setUp()
    {
        $this->parser = new OverrideContextParser;
    }

    /** @test */
    public function typeName()
    {
        $this->assertEquals('override-context', $this->parser->getTypeName());
    }

    public function xmlFragmentsProvider()
    {
        $xmls = array();
        $contexts = array();

        $xmls[] = <<< XML
<action />
XML;
        $contexts[] = array();

        $xmls[] = <<< XML
<action>text</action>
XML;
        $contexts[] = array();

        $xmls[] = <<< XML
<action>
    <foo>bar</foo>
    <bar>baz</bar>
</action>
XML;
        $contexts[] = array('foo' => 'bar', 'bar' => 'baz');

        $xmls[] = <<< XML
<action foo="bar" bar="baz" />
XML;
        $contexts[] = array('foo' => 'bar', 'bar' => 'baz');

        $xmls[] = <<< XML
<action>
    <foo bar="baz">text</foo>
</action>
XML;
        $contexts[] = array('foo' => array('bar' => 'baz'));

        $xmls[] = <<< XML
<action foo="xxx">
    <foo>yyy</foo>
</action>
XML;
        $contexts[] = array('foo' => array('xxx', 'yyy'));

        $xmls[] = <<< XML
<action foo="xxx">
    <foo>yyy</foo>
    <foo>zzz</foo>
</action>
XML;
        $contexts[] = array('foo' => array('xxx', 'yyy', 'zzz'));

        $xmls[] = <<< XML
<action foo="xxx">
    <foo>
        <bar>yyy</bar>
        <bar>zzz</bar>
    </foo>
</action>
XML;
        $contexts[] = array(
            'foo' => array(
                0 => 'xxx',
                'bar' => array('yyy', 'zzz')
            )
        );

        $xmls[] = <<< XML
<action foo="xxx">
    <foo>
        <bar>yyy</bar>
    </foo>
    <foo>
        <bar>zzz</bar>
    </foo>
</action>
XML;
        $contexts[] = array(
            'foo' => array(
                0 => 'xxx',
                'bar' => array('yyy', 'zzz')
            )
        );

        $provider = array();
        list(, $xml) = each($xmls);
        list(, $context) = each($contexts);
        while (!(is_null($xml) || is_null($context))) {
            $provider[] = array($context, $xml);
            list(, $xml) = each($xmls);
            list(, $context) = each($contexts);
        }
        return $provider;
    }

    /**
     * @test
     * @dataProvider xmlFragmentsProvider
     * @param array $expected
     * @param string $xml
     */
    public function overrideElements(array $expected, $xml)
    {
        $actionElement = $this->createActionElement($xml);

        $rule = new Rule();
        $action = $this->parser->parse($actionElement, $rule, new \DOMXPath($actionElement->ownerDocument));
        $this->assertSame($action, $rule->getAction());

        $context = $action->perform(new Context());
        $this->assertEquals($expected, iterator_to_array($context));
    }

    private function createActionElement($xml)
    {
        $doc = new \DOMDocument();
        $frag = $doc->createDocumentFragment();
        $frag->appendXML($xml);
        $doc->appendChild($frag);
        return $doc->documentElement;
    }
}
