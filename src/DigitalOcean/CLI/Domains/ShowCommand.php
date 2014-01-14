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
            ->setDescription('Show a specific domain in your account')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', Command::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $domain       = $digitalOcean->domains()->show($input->getArgument('id'))->domain;

        $content   = array();
        $content[] = array(
            $domain->id,
            $domain->name,
            $domain->ttl,
            preg_replace('/[ ]{2,}|[\t]/', '  ', trim($domain->live_zone_file)),
            $domain->error,
            preg_replace('/[ ]{2,}|[\t]/', '  ', trim($domain->zone_file_with_error)),
        );
        $table = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('ID', 'Name', 'TTL', 'Live Zone File', 'Error', 'Zone File With Error'))
            ->setRows($content);

        $table->render($output);
    }
}
