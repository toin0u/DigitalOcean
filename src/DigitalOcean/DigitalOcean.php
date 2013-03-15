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
use DigitalOcean\Droplets\Droplets;
use DigitalOcean\Regions\Regions;
use DigitalOcean\Images\Images;
use DigitalOcean\Sizes\Sizes;
use DigitalOcean\SSHKeys\SSHKeys;

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
    const VERSION = '0.1.1';


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
     * Alias to droplets object.
     *
     * @return Droplets instance.
     */
    public function droplets()
    {
        return new Droplets($this->clientId, $this->apiKey, $this->adapter);
    }

    /**
     * Alias to regions object.
     *
     * @return Regions instance.
     */
    public function regions()
    {
        return new Regions($this->clientId, $this->apiKey, $this->adapter);
    }

    /**
     * Alias to images object.
     *
     * @return Images instance.
     */
    public function images()
    {
        return new Images($this->clientId, $this->apiKey, $this->adapter);
    }

    /**
     * Alias to sizes object.
     *
     * @return Sizes instance.
     */
    public function sizes()
    {
        return new Sizes($this->clientId, $this->apiKey, $this->adapter);
    }

    /**
     * Alias to sshkeys object.
     *
     * @return SSHKeys instance.
     */
    public function sshKeys()
    {
        return new SSHKeys($this->clientId, $this->apiKey, $this->adapter);
    }
}
