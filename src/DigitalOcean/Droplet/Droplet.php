<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\Droplet;

use DigitalOcean\DigitalOcean;
use DigitalOcean\AbstractDigitalOcean;
use DigitalOcean\HttpAdapter\HttpAdapterInterface;

/**
 * Droplet class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class Droplet extends AbstractDigitalOcean
{
    /**
     * Droplets API name.
     *
     * @var string
     */
    const DROPLETS = 'droplets';


    /**
     * The credentials.
     *
     * @var array
     */
    private $credentials;

    /**
     * The droplet Id.
     *
     * @var integer
     */
    private $dropletId;

    /**
     * The API url.
     *
     * @var string
     */
    protected $apiUrl;


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
        $this->apiUrl  = sprintf("%s/%s", AbstractDigitalOcean::ENDPOINT_URL, self::DROPLETS);
        $this->adapter = $adapter;
    }

    /**
     * Builds the API url according to the parameters.
     *
     * @param integer $dropletId  The droplet Id (optional).
     * @param string  $action     The action to perform (optional).
     * @param array   $parameters An array of parameters (optional).
     *
     * @return string The built API url.
     */
    protected function buildQuery($dropletId = null, $action = null, array $parameters = array())
    {
        $parameters = http_build_query(array_merge($parameters, $this->credentials));

        $query = $dropletId ? sprintf("%s/%s", $this->apiUrl, $dropletId) : $this->apiUrl;
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

    /**
     * Returns all active droplets that are currently running in your account.
     * All available API information is presented for each droplet.
     *
     * @return StdClass
     */
    public function showAllActive()
    {
        return $this->processQuery($this->buildQuery());
    }

    /**
     * Returns full information for a specific droplet.
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function show($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId));
    }

    /**
     * Creates a new droplet.
     * The parameter should be an array with 4 required keys: name, sized_id, image_id and region_id.
     * The ssh_key_ids key is optional.
     *
     * @param array $parameters An array of parameters.
     *
     * @return StdClass
     *
     * @throws \InvalidArgumentException
     */
    public function create(array $parameters)
    {
        if (!array_key_exists('name', $parameters) || !is_string($parameters['name'])) {
            throw new \InvalidArgumentException('A new droplet must have a string "name".');
        }

        if (!array_key_exists('size_id', $parameters) || !is_int($parameters['size_id'])) {
            throw new \InvalidArgumentException('A new droplet must have an integer "size_id".');
        }

        if (!array_key_exists('image_id', $parameters) || !is_int($parameters['image_id'])) {
            throw new \InvalidArgumentException('A new droplet must have an integer "image_id".');
        }

        if (!array_key_exists('region_id', $parameters) || !is_int($parameters['region_id'])) {
            throw new \InvalidArgumentException('A new droplet must have an integer "region_id".');
        }

        return $this->processQuery($this->buildQuery(null, 'new', $parameters));
    }

    /**
     * Reboots a droplet.
     * This is the preferred method to use if a server is not responding.
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function reboot($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId), 'reboot');
    }

    /**
     * Power cycles a droplet.
     * This will turn off the droplet and then turn it back on.
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function powerCycle($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId), 'power_cycle');
    }

    /**
     * Shutdowns a running droplet.
     * The droplet will remain in your account.
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function shutdown($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId), 'shutdown');
    }

    /**
     * Powerons a powered off droplet.
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function powerOn($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId), 'power_on');
    }

    /**
     * Poweroffs a running droplet.
     * The droplet will remain in your account.
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function powerOff($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId), 'power_off');
    }

    /**
     * Resets the root password for a droplet.
     * Please be aware that this will reboot the droplet to allow resetting the password.
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function resetRootPassword($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId), 'password_reset');
    }

    /**
     * Resizes a specific droplet to a different size.
     * This will affect the number of processors and memory allocated to the droplet.
     * The size_id key is required.
     *
     * @param integer $dropletId  The id of the droplet.
     * @param array   $parameters An array of parameters.
     *
     * @return StdClass
     *
     * @throws \InvalidArgumentException
     */
    public function resize($dropletId, array $parameters)
    {
        if (!array_key_exists('size_id', $parameters) || !is_int($parameters['size_id'])) {
            throw new \InvalidArgumentException('You need to provide an integer "size_id".');
        }

        return $this->processQuery($this->buildQuery($dropletId), 'resize', $parameters);
    }

    /**
     * Takes a snapshot of the running droplet, which can later be restored or
     * used to create a new droplet from the same image.
     * Please be aware this may cause a reboot.
     * The name key is optional.
     *
     * @param integer $dropletId  The id of the droplet.
     * @param array   $parameters An array of parameters (optional).
     *
     * @return StdClass
     */
    public function snapshot($dropletId, array $parameters = array())
    {
        return $this->processQuery($this->buildQuery($dropletId), 'snapshot', $parameters);
    }

    /**
     * Restores a droplet with a previous image or snapshot.
     * This will be a mirror copy of the image or snapshot to your droplet.
     * Be sure you have backed up any necessary information prior to restore.
     * The image_id is required.
     *
     * @param integer $dropletId  The id of the droplet.
     * @param array   $parameters An array of parameters.
     *
     * @return StdClass
     *
     * @throws \InvalidArgumentException
     */
    public function restore($dropletId, array $parameters)
    {
        if (!array_key_exists('image_id', $parameters) || !is_int($parameters['image_id'])) {
            throw new \InvalidArgumentException('You need to provide the "image_id" to restore.');
        }

        return $this->processQuery($this->buildQuery($dropletId), 'restore', $parameters);
    }

    /**
     * Reinstalls a droplet with a default image.
     * This is useful if you want to start again but retain the same IP address for your droplet.
     * The image_id is required.
     *
     * @param integer $dropletId  The id of the droplet.
     * @param array   $parameters An array of parameters.
     *
     * @return StdClass
     *
     * @throws \InvalidArgumentException
     */
    public function rebuild($dropletId, array $parameters)
    {
        if (!array_key_exists('image_id', $parameters) || !is_int($parameters['image_id'])) {
            throw new \InvalidArgumentException('You need to provide the "image_id" to rebuild.');
        }

        return $this->processQuery($this->buildQuery($dropletId), 'rebuild', $parameters);
    }

    /**
     * Enables automatic backups which run in the background daily to backup your droplet's data.
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function enableAutomaticBackups($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId), 'enable_backups');
    }

    /**
     * Disables automatic backups from running to backup your droplet's data.
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function disableAutomaticBackups($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId), 'disable_backup');
    }

    /**
     * Destroys one of your droplets - this is irreversible !
     *
     * @param integer $dropletId The id of the droplet.
     *
     * @return StdClass
     */
    public function destroy($dropletId)
    {
        return $this->processQuery($this->buildQuery($dropletId), 'destroy');
    }
}

