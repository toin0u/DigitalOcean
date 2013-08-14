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

use HttpAdapter\HttpAdapterInterface;
use HttpAdapter\CurlHttpAdapter;
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
    const VERSION = '1.2.2-dev';


    /**
     * The credentials instance to use.
     *
     * @var Credentials
     */
    protected $credentials;


    /**
     * Constructor.
     *
     * @param Credentials          $credentials The credentials to use.
     * @param HttpAdapterInterface $adapter     The HttpAdapter to use (optional).
     */
    public function __construct(Credentials $credentials, HttpAdapterInterface $adapter = null)
    {
        $this->credentials = $credentials;
        $this->adapter     = $adapter ?: new CurlHttpAdapter();
    }

    /**
     * Alias to droplets object.
     *
     * @return Droplets instance.
     */
    public function droplets()
    {
        return new Droplets($this->credentials, $this->adapter);
    }

    /**
     * Alias to regions object.
     *
     * @return Regions instance.
     */
    public function regions()
    {
        return new Regions($this->credentials, $this->adapter);
    }

    /**
     * Alias to images object.
     *
     * @return Images instance.
     */
    public function images()
    {
        return new Images($this->credentials, $this->adapter);
    }

    /**
     * Alias to sizes object.
     *
     * @return Sizes instance.
     */
    public function sizes()
    {
        return new Sizes($this->credentials, $this->adapter);
    }

    /**
     * Alias to sshkeys object.
     *
     * @return SSHKeys instance.
     */
    public function sshKeys()
    {
        return new SSHKeys($this->credentials, $this->adapter);
    }
}
