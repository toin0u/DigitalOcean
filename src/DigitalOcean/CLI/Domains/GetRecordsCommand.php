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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use DigitalOcean\CLI\Command;

/**
 * Command-line domains:records:all class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class GetRecordsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('domains:records:all')
            ->addArgument('id', InputArgument::REQUIRED, 'The id or the name of the domain')
            ->setDescription('Return all of your current domain records')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', Command::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result       = array();
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $records      = $digitalOcean->domains()->getRecords($input->getArgument('id'))->records;

        foreach ($records as $i => $record) {
            $result[] = sprintf(
                '%s | id:<value>%s</value> | domain_id:<value>%s</value> | record_type:<value>%s</value> | name:<value>%s</value> | data:<value>%s</value> | priority:<value>%s</value> | port:<value>%s</value> | weight:<value>%s</value>',
                ++$i, $record->id, $record->domain_id, $record->record_type, $record->name, $record->data, $record->priority, $record->port, $record->weight
            );
        }

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
