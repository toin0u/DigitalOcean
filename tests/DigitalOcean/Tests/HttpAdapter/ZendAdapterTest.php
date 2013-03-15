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
use DigitalOcean\HttpAdapter\ZendHttpAdapter;
use Zend\Http\Client;

/**
 * @author William Durand <william.durand1@gmail.com>
 * @author Antoine Corcy <contact@sbin.dk>
 */
class ZendHttpAdapterTest extends TestCase
{
    protected $zend;

    protected function setUp()
    {
        if (!class_exists('Zend\Http\Client')) {
            $this->markTestSkipped('Zend library has to be installed');
        }

        $this->zend = new ZendHttpAdapter();
    }

    public function testGetNullContent()
    {
        $this->assertNull($this->zend->getContent(null));
    }

    public function testGetFalseContent()
    {
        $this->assertNull($this->zend->getContent(false));
    }

    public function testGetName()
    {
        $this->assertEquals('zend', $this->zend->getName());
    }

    public function testGetContent()
    {
        try {
            $content = $this->zend->getContent('http://www.google.fr');
        } catch (\Exception $e) {
            $this->fail('Exception catched: ' . $e->getMessage());
        }

        $this->assertNotNull($content);
        $this->assertContains('google', $content);
    }
}
