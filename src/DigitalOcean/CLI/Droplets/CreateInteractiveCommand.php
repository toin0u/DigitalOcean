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
 * Command-line droplets:create class.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 */
class CreateInteractiveCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('droplets:create-interactively')
            ->setDescription('Create interactively a new droplet')
            ->addOption('credentials', null, InputOption::VALUE_REQUIRED,
                'If set, the yaml file which contains your credentials', COMMAND::DEFAULT_CREDENTIALS_FILE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digitalOcean = $this->getDigitalOcean($input->getOption('credentials'));

        $dialog = $this->getHelperSet()->get('dialog');

        // the droplet's name
        $name = $dialog->ask(
            $output,
            '<question>Please enter the name of the new droplet:</question> '
        );
        if (null === $name) {
            $output->writeln('Aborted!');
            return;
        }

        // the droplet's size
        foreach ($digitalOcean->sizes()->getAll()->sizes as $size) {
            $sizes[$size->id] = $size->name;
        }
        $sizeId = (int) $dialog->select(
            $output,
            '<question>Please select the size:</question> ',
            $sizes
        );

        // the droplet's region
        foreach ($digitalOcean->regions()->getAll()->regions as $region) {
            $regions[$region->id] = $region->name;
        }
        $regionId = (int) $dialog->select(
            $output,
            '<question>Please select the region:</question> ',
            $regions
        );

        // available images
        $typeImageId = $dialog->select(
            $output,
            '<question>Please select your image type:</question> ',
            array('Global images (default)', 'Your images', 'All images'),
            0
        );
        switch ($typeImageId) {
            case 0:
                $availableImages = $digitalOcean->images()->getGlobal();
                break;
            case 1:
                $availableImages = $digitalOcean->images()->getMyImages();
                break;
            case 2:
                $availableImages = $digitalOcean->images()->getAll();
                break;
        }
        foreach ($availableImages->images as $image) {
            $images[$image->id] = sprintf('%s, %s', $image->name, $image->distribution);
        }
        $imageId = (int) $dialog->select(
            $output,
            '<question>Please select the image:</question> ',
            $images
        );

        // the droplet's public SSH key to associate
        $sshKeys = array(0 => 'None (default)');
        foreach ($digitalOcean->sshkeys()->getAll()->ssh_keys as $sshKey) {
            $sshKeys[$sshKey->id] = $sshKey->name;
        }
        $sshKeyId = $dialog->select(
            $output,
            '<question>Please select the SSH key to associate with your droplet: </question>',
            $sshKeys,
            0
        );
        $sshKeyId = '0' !== $sshKeyId ? $sshKeyId : '';

        $confirmation = <<<EOT
Name:    <info>$name</info>
Size:    <info>{$sizes[$sizeId]}</info>
Region:  <info>{$regions[$regionId]}</info>
Image:   <info>{$images[$imageId]}</info>
SSH key: <info>{$sshKeys[$sshKeyId]}</info>
<question>Are you sure to create this droplet ? (y/N)</question> 
EOT
        ;

        if (!$dialog->askConfirmation(
                $output,
                $confirmation,
                false
            )) {
            $output->writeln('Aborted!');
            return;
        }

        $droplet = $digitalOcean->droplets()->create(array(
            'name'        => $name,
            'size_id'     => $sizeId,
            'image_id'    => $imageId,
            'region_id'   => $regionId,
            'ssh_key_ids' => $sshKeyId,
        ));

        $result[] = sprintf('status:   <value>%s</value>', $droplet->status);
        $result[] = sprintf('event_id: <value>%s</value>', $droplet->droplet->event_id);

        $output->getFormatter()->setStyle('value', new OutputFormatterStyle('green', 'black'));
        $output->writeln($result);
    }
}
