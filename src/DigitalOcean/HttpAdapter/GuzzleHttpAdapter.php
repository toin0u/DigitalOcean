<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\HttpAdapter;

use Guzzle\Service\Client;

/**
 * GuzzleHttpAdapter class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class GuzzleHttpAdapter implements HttpAdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getContent($url)
    {
        $guzzle = new Client();

        try {
            $content = (string) $guzzle->get($url)->send()->getBody();
        } catch (\Exception $e) {
            $content = null;
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'guzzle';
    }
}
