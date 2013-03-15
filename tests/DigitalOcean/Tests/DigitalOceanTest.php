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

use DigitalOcean\DigitalOcean;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class DigitalOceanTest extends TestCase
{
    public function testReturnsDropletInstance()
    {
        $digitalOcean = new DigitalOcean('clientId', 'apiKey', $this->getMockAdapter($this->never()));
        $droplet = $digitalOcean->droplet();

        $this->assertTrue(is_object($droplet));
        $this->assertInstanceOf('\\DigitalOcean\\Droplet\\Droplet', $droplet);
    }

    public function testReturnsRegionInstance()
    {
        $digitalOcean = new DigitalOcean('clientId', 'apiKey', $this->getMockAdapter($this->never()));
        $region = $digitalOcean->region();

        $this->assertTrue(is_object($region));
        $this->assertInstanceOf('\\DigitalOcean\\Region\\Region', $region);
    }

    public function testReturnsImageInstance()
    {
        $digitalOcean = new DigitalOcean('clientId', 'apiKey', $this->getMockAdapter($this->never()));
        $image = $digitalOcean->image();

        $this->assertTrue(is_object($image));
        $this->assertInstanceOf('\\DigitalOcean\\Image\\Image', $image);
    }

    public function testReturnsSizeInstance()
    {
        $digitalOcean = new DigitalOcean('clientId', 'apiKey', $this->getMockAdapter($this->never()));
        $size = $digitalOcean->size();

        $this->assertTrue(is_object($size));
        $this->assertInstanceOf('\\DigitalOcean\\Size\\Size', $size);
    }

    public function testReturnsSSHKeysInstance()
    {
        $digitalOcean = new DigitalOcean('clientId', 'apiKey', $this->getMockAdapter($this->never()));
        $sshKeys = $digitalOcean->sshKeys();

        $this->assertTrue(is_object($sshKeys));
        $this->assertInstanceOf('\\DigitalOcean\\SSHKeys\\SSHKeys', $sshKeys);
    }
}
