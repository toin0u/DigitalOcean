<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\CLI\Domains;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use DigitalOcean\CLI\Command;

/**
 * Command-line domains:all class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class GetAllCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('domains:all')
            ->setDescription('Return all of your current domains')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result       = array();
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $domains      = $digitalOcean->domains()->getAll()->domains;

        foreach ($domains as $i => $domain) {
            $result[] = sprintf(
                '%s | id:<value>%s</value> | name:<value>%s</value> | ttl:<value>%s</value> | live_zone_file:<value>%s</value> | error:<value>%s</value> | zone_file_with_error:<value>%s</value>',
                ++$i, $domain->id, $domain->name, $domain->ttl, $domain->live_zone_file, $domain->error, $domain->zone_file_with_error
            );
        }

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
