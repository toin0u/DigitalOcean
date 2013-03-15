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

use Zend\Http\Client;

/**
 * ZendHttpAdapter class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class ZendHttpAdapter implements HttpAdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getContent($url)
    {
        $zend = new Client();

        try {
            $response = $zend->setUri($url)->send();
            $content  = $response->isSuccess() ? $response->getBody() : null;
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
        return 'zend';
    }
}
