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
use DigitalOcean\HttpAdapter\GuzzleHttpAdapter;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class GuzzleHttpAdapterTest extends TestCase
{
    protected $guzzle;

    protected function setUp()
    {
        if (!class_exists('Guzzle\Service\Client')) {
            $this->markTestSkipped('Guzzle library has to be installed');
        }

        $this->guzzle = new GuzzleHttpAdapter();
    }

    public function testGetNullContent()
    {
        $this->assertNull($this->guzzle->getContent(null));
    }

    public function testGetFalseContent()
    {
        $this->assertNull($this->guzzle->getContent(false));
    }

    public function testGetName()
    {
        $this->assertEquals('guzzle', $this->guzzle->getName());
    }
}
