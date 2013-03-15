<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\Tests\Region;

use DigitalOcean\Tests\TestCase;
use DigitalOcean\Region\Region;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class RegionTest extends TestCase
{
    protected $clientId;
    protected $apiKey;

    protected function setUp()
    {
        $this->clientId = 'foo';
        $this->apiKey   = 'bar';
    }

    public function testRegionsUrl()
    {
        $region = new Region($this->clientId, $this->apiKey, $this->getMockAdapter($this->never()));

        $method = new \ReflectionMethod(
            $region, 'buildQuery'
        );
        $method->setAccessible(true);

        $this->assertEquals(
            'https://api.digitalocean.com/regions/?client_id=foo&api_key=bar',
            $method->invoke($region)
        );
    }

    public function testRegions()
    {
        $response = <<<JSON
{"status":"OK","regions":[{"id":1,"name":"New York 1"},{"id":2,"name":"Amsterdam 1"}]}
JSON
        ;

        $region  = new Region($this->clientId, $this->apiKey, $this->getMockAdapterReturns($response));
        $regions = $region->regions();

        $this->assertTrue(is_object($regions));
        $this->assertEquals('OK', $regions->status);
        $this->assertCount(2, $regions->regions);

        $region1 = $regions->regions[0];
        $this->assertSame(1, $region1->id);
        $this->assertSame('New York 1', $region1->name);

        $region2 = $regions->regions[1];
        $this->assertSame(2, $region2->id);
        $this->assertSame('Amsterdam 1', $region2->name);
    }
}
