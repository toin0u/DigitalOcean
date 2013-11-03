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
 * Command-line domains:records:show class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class ShowRecordCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('domains:records:show')
            ->addArgument('id', InputArgument::REQUIRED, 'The id or the name of the domain')
            ->addArgument('record_id', InputArgument::REQUIRED, 'The id of the record')
            ->setDescription('Show a specified domain record in your account')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', Command::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $record       = $digitalOcean->domains()->getRecord($input->getArgument('id'), $input->getArgument('record_id'));

        $result[] = sprintf('status:      <value>%s</value>', $record->status);
        $result[] = sprintf('id:          <value>%s</value>', $record->record->id);
        $result[] = sprintf('domain_id:   <value>%s</value>', $record->record->domain_id);
        $result[] = sprintf('record_type: <value>%s</value>', $record->record->record_type);
        $result[] = sprintf('name:        <value>%s</value>', $record->record->name);
        $result[] = sprintf('data:        <value>%s</value>', $record->record->data);
        $result[] = sprintf('priority:    <value>%s</value>', $record->record->priority);
        $result[] = sprintf('port:        <value>%s</value>', $record->record->port);
        $result[] = sprintf('weight:      <value>%s</value>', $record->record->weight);

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
