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
 * Command-line droplets:destroy class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class DestroyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('droplets:destroy')
            ->setDescription('Destroy one of your droplets - this is irreversible !')
            ->addArgument('id', InputArgument::REQUIRED, 'The droplet id')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->getHelperSet()->get('dialog')->askConfirmation(
                $output,
                sprintf('<question>Are you sure to destroy droplet %s ? (y/N) </question>', $input->getArgument('id')),
                false
            )) {
            $output->writeln('Aborted!');
            return;
        }

        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $droplet      = $digitalOcean->droplets()->destroy($input->getArgument('id'));

        $result[] = sprintf('status:   <value>%s</value>', $droplet->status);
        $result[] = sprintf('event_id: <value>%s</value>', $droplet->event_id);

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
