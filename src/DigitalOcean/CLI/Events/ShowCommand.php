<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\CLI\Events;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use DigitalOcean\CLI\Command;

/**
 * Command-line events:show class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class ShowCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('events:show')
            ->addArgument('id', InputArgument::REQUIRED, 'The id of the event')
            ->setDescription('Reports on the progress of an event')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $event        = $digitalOcean->events()->show($input->getArgument('id'))->event;

        $result[] = sprintf('id:                   <value>%s</value>', $event->id);
        $result[] = sprintf('action_status:        <value>%s</value>', $event->action_status);
        $result[] = sprintf('droplet_id:           <value>%s</value>', $event->droplet_id);
        $result[] = sprintf('event_type_id:        <value>%s</value>', $event->event_type_id);
        $result[] = sprintf('percentage:           <value>%s</value>', $event->percentage);

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
