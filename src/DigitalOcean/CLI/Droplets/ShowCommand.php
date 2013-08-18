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
 * Command-line droplets:show class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class ShowCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('droplets:show')
            ->setDescription('Return full information for a specific droplet')
            ->addArgument('id', InputArgument::REQUIRED, 'The droplet id')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $droplet      = $digitalOcean->droplets()->show($input->getArgument('id'))->droplet;

        $result[] = sprintf('id:             <value>%s</value>', $droplet->id);
        $result[] = sprintf('name:           <value>%s</value>', $droplet->name);
        $result[] = sprintf('image_id:       <value>%s</value>', $droplet->image_id);
        $result[] = sprintf('size_id:        <value>%s</value>', $droplet->size_id);
        $result[] = sprintf('region_id:      <value>%s</value>', $droplet->region_id);
        $result[] = sprintf('backups_active: <value>%s</value>', $droplet->backups_active);
        $result[] = sprintf('ip_address:     <value>%s</value>', $droplet->ip_address);
        $result[] = sprintf('status:         <value>%s</value>', $droplet->status);
        $result[] = sprintf('locked:         <value>%s</value>', $droplet->locked);
        $result[] = sprintf('created_at:     <value>%s</value>', $droplet->created_at);

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
