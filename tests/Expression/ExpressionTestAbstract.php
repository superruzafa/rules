<?php

namespace Superruzafa\Rules\Expression;

use PHPUnit_Framework_MockObject_Stub;
use Superruzafa\Rules\Expression;

abstract class ExpressionTestAbstract extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Expression|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getExpressionMock()
    {
        return $this
            ->getMockBuilder('Superruzafa\\Rules\\Expression')
            ->setMethods(array('evaluate', 'getNativeExpression'))
            ->getMockForAbstractClass();
    }

    protected function getEvaluateMock($value, $expects = null)
    {
        $mock = $this->getExpressionMock();

        if (is_int($expects) || $expects instanceof \PHPUnit_Framework_MockObject_Matcher_Invocation) {
            $invokation = $mock->expects($expects);
        } else {
            $invokation = $mock->expects($this->any());
        }

        $invokation->method('evaluate');

        if ($value instanceof PHPUnit_Framework_MockObject_Stub) {
            $invokation->will($value);
        } else {
            $invokation->will($this->returnValue($value));
        }

        return $mock;
    }

    protected function getNativeExpressionMock($value, $expects = null)
    {
        $mock = $this->getExpressionMock();

        if (is_int($expects) || $expects instanceof \PHPUnit_Framework_MockObject_Matcher_Invocation) {
            $invokation = $mock->expects($expects);
        } else {
            $invokation = $mock->expects($this->any());
        }

        $invokation->method('getNativeExpression');

        if ($value instanceof PHPUnit_Framework_MockObject_Stub) {
            $invokation->will($value);
        } else {
            $invokation->will($this->returnValue($value));
        }

        return $mock;
    }
}
