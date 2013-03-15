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
use DigitalOcean\Region\Region;
use DigitalOcean\Image\Image;
use DigitalOcean\Size\Size;
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
    const VERSION = '0.1.0';


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

    /**
     * Alias to region object.
     *
     * @return Region instance.
     */
    public function region()
    {
        return new Region($this->clientId, $this->apiKey, $this->adapter);
    }

    /**
     * Alias to image object.
     *
     * @return Image instance.
     */
    public function image()
    {
        return new Image($this->clientId, $this->apiKey, $this->adapter);
    }

    /**
     * Alias to size object.
     *
     * @return Size instance.
     */
    public function size()
    {
        return new Size($this->clientId, $this->apiKey, $this->adapter);
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
