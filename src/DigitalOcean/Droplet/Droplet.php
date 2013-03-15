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
use DigitalOcean\Droplet\DropletActions;
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
     * Constructor.
     *
     * @param string               $clientId The cliend ID.
     * @param string               $apiKey   The API key.
     * @param HttpAdapterInterface $adapter  The HttpAdapter to use.
     */
    public function __construct($clientId, $apiKey, HttpAdapterInterface $adapter)
    {
        parent::__construct($clientId, $apiKey, $adapter);

        $this->apiUrl  = sprintf("%s/%s", AbstractDigitalOcean::ENDPOINT_URL, self::DROPLETS);
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

        return $this->processQuery($this->buildQuery(null, DropletActions::ACTION_NEW, $parameters));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_REBOOT));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_POWER_CYCLE));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_SHUTDOWN));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_POWER_ON));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_POWER_OFF));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_RESET_ROOT_PASSWORD));
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

        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_RESIZE, $parameters));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_SNAPSHOT, $parameters));
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

        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_RESTORE, $parameters));
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

        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_REBUILD, $parameters));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_ENABLE_BACKUPS));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_DISABLE_BACKUPS));
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
        return $this->processQuery($this->buildQuery($dropletId, DropletActions::ACTION_DESTROY));
    }
}

