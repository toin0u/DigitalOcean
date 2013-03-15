<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\SSHKeys;

use DigitalOcean\SSHKeys\SSHKeysActions;
use DigitalOcean\AbstractDigitalOcean;
use DigitalOcean\HttpAdapter\HttpAdapterInterface;

/**
 * SSHKeys class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class SSHKeys extends AbstractDigitalOcean
{
    /**
     * SSHKeys API name.
     *
     * @var string
     */
    const SSH_KEYS = 'ssh_keys';


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

        $this->apiUrl = sprintf("%s/%s", $this->apiUrl, self::SSH_KEYS);
    }

    /**
     * Returns all the available public SSH keys in your account that can be added to a droplet.
     *
     * @return StdClass
     */
    public function getAll()
    {
        return $this->processQuery($this->buildQuery());
    }

    /**
     * Shows a specific public SSH key in your account that can be added to a droplet.
     *
     * @param integer $sshKeyId The id of the SSH key.
     *
     * @return StdClass
     */
    public function show($sshKeyId)
    {
        return $this->processQuery($this->buildQuery($sshKeyId));
    }

    /**
     * Adds a new public SSH key to your account.
     * The array requires name and ssh_key_pub keys.
     *
     * @param array $parameters An array of parameters.
     *
     * @return StdClass
     *
     * @throws \InvalidArgumentException
     */
    public function add(array $parameters)
    {
        if (!array_key_exists('name', $parameters) || !is_string($parameters['name'])) {
            throw new \InvalidArgumentException('You need to provide the name of the SSH Key.');
        }

        if (!array_key_exists('ssh_key_pub', $parameters) || !is_string($parameters['ssh_key_pub'])) {
            throw new \InvalidArgumentException('You need to provide the SSH key.');
        }

        return $this->processQuery($this->buildQuery(null, SSHKeysActions::ACTION_ADD, $parameters));
    }

    /**
     * Edits an existing public SSH key in your account.
     * The array requires ssh_key_pub key.
     *
     * @param array $parameters An array of parameters.
     *
     * @return StdClass
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function edit(array $parameters)
    {
        if (!array_key_exists('ssh_key_pub', $parameters) || !is_string($parameters['ssh_key_pub'])) {
            throw new \InvalidArgumentException('You need to provide the new public SSH Key.');
        }

        throw new \RuntimeException('Not implemented yet. Coming soon...');
    }

    /**
     * Deletes the SSH key from your account.
     *
     * @param integer $sshKeyId The id of the SSH key.
     *
     * @return StdClass
     */
    public function destroy($sshKeyId)
    {
        return $this->processQuery($this->buildQuery($sshKeyId, SSHKeysActions::ACTION_DESTROY));
    }
}
