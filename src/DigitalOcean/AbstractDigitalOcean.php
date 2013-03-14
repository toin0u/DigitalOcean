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

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class AbstractDigitalOcean
{
    /**
     * The url of the API endpoint.
     *
     * @var string
     */
    const ENDPOINT_URL = 'https://api.digitalocean.com';


    /**
     * The adapter to use.
     *
     * @var HttpAdapterInterface
     */
    protected $adapter;
}
