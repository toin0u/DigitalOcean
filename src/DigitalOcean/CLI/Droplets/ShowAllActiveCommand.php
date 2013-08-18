<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\CLI\Droplets;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use DigitalOcean\CLI\Command;

/**
 * Command-line droplets:show-all-active class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class ShowAllActiveCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('droplets:show-all-active')
            ->setDescription('Return all active droplets that are currently running in your account')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result       = array();
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $droplets     = $digitalOcean->droplets()->showAllActive()->droplets;

        foreach ($droplets as $i => $droplet) {
            $result[] = sprintf(
                '%s | id:<value>%s</value> | name:<value>%s</value> | image_id:<value>%s</value> | size_id:<value>%s</value> | region_id:<value>%s</value> | backups_active:<value>%s</value> | ip_address:<value>%s</value> | status:<value>%s</value> | locked:<value>%s</value> | created_at:<value>%s</value>',
                ++$i, $droplet->id, $droplet->name, $droplet->image_id, $droplet->size_id, $droplet->region_id, $droplet->backups_active, $droplet->ip_address, $droplet->status, $droplet->locked, $droplet->created_at
            );
        }

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
