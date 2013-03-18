<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\CLI\Regions;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use DigitalOcean\CLI\Command;

/**
 * Command-line regions:all class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class GetAllCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('regions:all')
            ->setDescription('Return all the available regions within the Digital Ocean cloud')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $regions      = $digitalOcean->regions()->getAll()->regions;

        foreach ($regions as $i => $region) {
            $result[] = sprintf('%s | id: <value>%s</value> | name: <value>%s</value>', ++$i, $region->id, $region->name);
        }

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
