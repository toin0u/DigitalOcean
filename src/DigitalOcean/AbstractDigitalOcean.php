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

/**
 * DigitalOcean abstract class.
 *
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
     * The credentials.
     *
     * @var array
     */
    protected $credentials;

    /**
     * The Id of the element to work with.
     *
     * @var integer
     */
    protected $id;

    /**
     * The API url.
     *
     * @var string
     */
    protected $apiUrl;

    /**
     * The adapter to use.
     *
     * @var HttpAdapterInterface
     */
    protected $adapter;


    /**
     * Constructor.
     *
     * @param string               $clientId The cliend ID.
     * @param string               $apiKey   The API key.
     * @param HttpAdapterInterface $adapter  The HttpAdapter to use.
     */
    public function __construct($clientId, $apiKey, HttpAdapterInterface $adapter)
    {
        $this->credentials = array(
            'client_id' => $clientId,
            'api_key'   => $apiKey,
        );
        $this->adapter = $adapter;
    }

    /**
     * Builds the API url according to the parameters.
     *
     * @param integer $id         The Id of the element to work with (optional).
     * @param string  $action     The action to perform (optional).
     * @param array   $parameters An array of parameters (optional).
     *
     * @return string The built API url.
     */
    protected function buildQuery($id = null, $action = null, array $parameters = array())
    {
        $parameters = http_build_query(array_merge($parameters, $this->credentials));

        $query = $id ? sprintf("%s/%s", $this->apiUrl, $id) : $this->apiUrl;
        $query = $action ? sprintf("%s/%s/?%s", $query, $action, $parameters) : sprintf("%s/?%s", $query, $parameters);

        return $query;
    }

    /**
     * Processes the query.
     *
     * @param string $query The query to process.
     *
     * @return StdClass
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function processQuery($query)
    {
        $processed = json_decode($this->adapter->getContent($query));

        if ('ERROR' === $processed->status) {
            if (isset($processed->description)) {
                throw new \InvalidArgumentException($processed->description);
            }

            throw new \RuntimeException($processed->error_message);
        }

        return $processed;
    }
}
