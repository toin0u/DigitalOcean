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
 * Command-line domains:show class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class ShowCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('domains:show')
            ->addArgument('id', InputArgument::REQUIRED, 'The id or the name of the domain')
            ->setDescription('Shows a specific domain in your account')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $domain       = $digitalOcean->domains()->show($input->getArgument('id'))->domain;

        $result[] = sprintf('id:                   <value>%s</value>', $domain->id);
        $result[] = sprintf('name:                 <value>%s</value>', $domain->name);
        $result[] = sprintf('ttl:                  <value>%s</value>', $domain->ttl);
        $result[] = sprintf('live_zone_file:       <value>%s</value>', $domain->live_zone_file);
        $result[] = sprintf('error:                <value>%s</value>', $domain->error);
        $result[] = sprintf('zone_file_with_error: <value>%s</value>', $domain->zone_file_with_error);

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
