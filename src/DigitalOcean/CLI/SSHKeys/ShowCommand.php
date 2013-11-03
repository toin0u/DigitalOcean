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
 * Command-line ssh-keys:show class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class ShowCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ssh-keys:show')
            ->addArgument('id', InputArgument::REQUIRED, 'The SSH key id')
            ->setDescription('Show a specific public SSH key in your account')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', Command::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $sshKey       = $digitalOcean->sshKeys()->show($input->getArgument('id'))->ssh_key;

        $result[] = sprintf('id:   <value>%s</value>', $sshKey->id);
        $result[] = sprintf('name: <value>%s</value>', $sshKey->name);
        $result[] = sprintf('key:  <value>%s</value>', $sshKey->ssh_pub_key);

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
