<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\CLI;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Yaml\Yaml;
use DigitalOcean\Credentials;
use DigitalOcean\DigitalOcean;

/**
 * Command class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class Command extends BaseCommand
{
    /**
     * The default file with credentials.
     *
     * @var string
     */
    const DEFAULT_CREDENTIALS_FILE = './credentials.yml';


    /**
     * Returns an instance of DigitalOcean
     *
     * @param string $file The file with credentials.
     *
     * @return DigitalOcean An instance of DigitalOcean
     */
    public function getDigitalOcean($file = self::DEFAULT_CREDENTIALS_FILE)
    {
        $credentials = Yaml::parse($file);

        return new DigitalOcean(new Credentials($credentials['CLIENT_ID'], $credentials['API_KEY']));
    }
}
