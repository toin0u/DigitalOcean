<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\Tests\HttpAdapter;

use DigitalOcean\Tests\TestCase;
use DigitalOcean\HttpAdapter\CurlHttpAdapter;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class CurlHttpAdapterTest extends TestCase
{
    protected $curl;

    protected function setUp()
    {
        $this->curl = new CurlHttpAdapter();
    }

    public function testGetNullContent()
    {
        $this->assertNull($this->curl->getContent(null));
    }

    public function testGetFalseContent()
    {
        $this->assertNull($this->curl->getContent(null));
    }

    public function testGetName()
    {
        $this->assertEquals('curl', $this->curl->getName());
    }
}