<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\Tests;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return HttpAdapterInterface
     */
    protected function getMockAdapter($expects = null)
    {
        if (null === $expects) {
            $expects = $this->once();
        }

        $mock = $this->getMock('\DigitalOcean\HttpAdapter\HttpAdapterInterface');
        $mock
            ->expects($expects)
            ->method('getContent')
            ->will($this->returnArgument(0));

        return $mock;
    }

    /**
     * @return HttpAdapterInterface
     */
    protected function getMockAdapterReturns($returnValue)
    {
        $mock = $this->getMock('\DigitalOcean\HttpAdapter\HttpAdapterInterface');
        $mock
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($returnValue));

        return $mock;
    }
}
