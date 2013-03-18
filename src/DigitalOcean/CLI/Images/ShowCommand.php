<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\CLI\Images;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use DigitalOcean\CLI\Command;

/**
 * Command-line images:show class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class ShowCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('images:show')
            ->setDescription('Display the attributes of an image')
            ->addArgument('id', InputArgument::REQUIRED, 'The image id')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $image        = $digitalOcean->images()->show($input->getArgument('id'))->image;

        $result[] = sprintf('id:           <value>%s</value>', $image->id);
        $result[] = sprintf('name:         <value>%s</value>', $image->name);
        $result[] = sprintf('distribution: <value>%s</value>', $image->distribution);

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
