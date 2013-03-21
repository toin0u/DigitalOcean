<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\CLI\SSHKeys;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use DigitalOcean\CLI\Command;

/**
 * Command-line ssh-keys:edit class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class EditCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ssh-keys:edit')
            ->addArgument('ssh_key_pub', InputArgument::REQUIRED, 'The new public SSH key')
            ->setDescription('Edit an existing public SSH key in your accoun')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $digitalOcean->sshKeys()->edit(array(
            'ssh_key_pub' => $input->getArgument('ssh_key_pub'),
        ));
    }
}
