<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\Sizes;

use DigitalOcean\AbstractDigitalOcean;
use DigitalOcean\HttpAdapter\HttpAdapterInterface;

/**
 * Sizes class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class Sizes extends AbstractDigitalOcean
{
    /**
     * Sizes API name.
     *
     * @var string
     */
    const SIZES = 'sizes';


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

        $this->apiUrl = sprintf("%s/%s", $this->apiUrl, self::SIZES);
    }

    /**
     * Returns all the available sizes that can be used to create a droplet.
     *
     * @return StdClass
     */
    public function getAll()
    {
        return $this->processQuery($this->buildQuery());
    }
}
