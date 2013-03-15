<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\Region;

use DigitalOcean\Droplet\DropletActions;
use DigitalOcean\AbstractDigitalOcean;
use DigitalOcean\HttpAdapter\HttpAdapterInterface;

/**
 * Region class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class Region extends AbstractDigitalOcean
{
    /**
     * Regions API name.
     *
     * @var string
     */
    const REGIONS = 'regions';


    /**
     * Constructor.
     *
     * @param string               $clientId The cliend ID.
     * @param string               $apiKey   The API key.
     * @param HttpAdapterInterface $adapter  The HttpAdapter to use.
     */
    public function __construct($clientId, $apiKey, HttpAdapterInterface $adapter)
    {
        parent::__construct($clientId, $apiKey, $adapter);

        $this->apiUrl  = sprintf("%s/%s", AbstractDigitalOcean::ENDPOINT_URL, self::REGIONS);
    }

    /**
     * Returns all the available regions within the Digital Ocean cloud.
     *
     * @return StdClass
     */
    public function getAll()
    {
        return $this->processQuery($this->buildQuery());
    }
}
