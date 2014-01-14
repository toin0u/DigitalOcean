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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use DigitalOcean\CLI\Command;

/**
 * Command-line droplets:disable-automatic-backups class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class DisableAutomaticBackupsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('droplets:disable-automatic-backups')
            ->setDescription('Disable automatic backups from running to backup your droplet\'s data.')
            ->addArgument('id', InputArgument::REQUIRED, 'The droplet id')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', Command::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $droplet      = $digitalOcean->droplets()->disableAutomaticBackups($input->getArgument('id'));

        $content   = array();
        $content[] = array($droplet->status, $droplet->event_id,);
        $table     = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Status', 'Event ID'))
            ->setRows($content);

        $table->render($output);
    }
}
