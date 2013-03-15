<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean;

use DigitalOcean\HttpAdapter\HttpAdapterInterface;
use DigitalOcean\Droplet\Droplet;

/**
 * DigitalOcean class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class DigitalOcean
{
    /**
     * Version.
     * @see http://semver.org/
     */
    const VERSION = '0.1.0-dev';


    /**
     * The client ID.
     *
     * @var string
     */
    private $clientId;

    /**
     * The API key.
     *
     * @var string
     */
    private $apiKey;


    /**
     * Constructor.
     *
     * @param string               $clientId The cliend ID.
     * @param string               $apiKey   The API key.
     * @param HttpAdapterInterface $adapter  The HttpAdapter to use.
     */
    public function __construct($clientId, $apiKey, HttpAdapterInterface $adapter)
    {
        $this->clientId = $clientId;
        $this->apiKey   = $apiKey;
        $this->adapter  = $adapter;
    }

    /**
     * Alias to droplet object.
     *
     * @return Droplet instance.
     */
    public function droplet()
    {
        return new Droplet($this->clientId, $this->apiKey, $this->adapter);
    }
}
