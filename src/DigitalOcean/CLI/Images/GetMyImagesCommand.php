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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use DigitalOcean\CLI\Command;

/**
 * Command-line images:mines class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class GetMyImagesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('images:mines')
            ->setDescription('Return all your images')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));
        $images       = $digitalOcean->images()->getMyImages()->images;

        foreach ($images as $i => $image) {
            $result[] = sprintf(
                '%s | id:<value>%s</value> | name:<value>%s</value> | distribution:<value>%s</value>',
                ++$i, $image->id, $image->name, $image->distribution
            );
        }

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
