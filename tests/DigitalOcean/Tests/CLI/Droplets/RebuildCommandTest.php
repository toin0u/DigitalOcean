<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\Tests\CLI\Droplets;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use DigitalOcean\Tests\TestCase;
use DigitalOcean\CLI\Droplets\RebuildCommand;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class RebuildCommandTest extends TestCase
{
    protected $application;
    protected $command;
    protected $commandTester;

    protected function setUp()
    {
        $this->application = new Application();

        $result = (object) array(
            'status'   => 'OK',
            'event_id' => 1234,
        );

        $RebuildCommand = $this->getMock('\DigitalOcean\CLI\Droplets\RebuildCommand', array('getDigitalOcean'));
        $RebuildCommand
            ->expects($this->any())
            ->method('getDigitalOcean')
            ->will($this->returnValue($this->getMockDigitalOcean('droplets', $this->getMockDroplets('rebuild', $result))));

        $this->application->add($RebuildCommand);

        $this->command = $this->application->find('droplets:rebuild');

        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function testExecuteNotEnoughArguments()
    {
        $this->commandTester->execute(array(
            'command' => $this->command->getName(),
        ));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function testExecuteNotEnoughArgumentsWithoutImageId()
    {
        $this->commandTester->execute(array(
            'command' => $this->command->getName(),
            'id'      => 123,
        ));
    }

    public function testExecuteCheckStatus()
    {
        $this->commandTester->execute(array(
            'command'  => $this->command->getName(),
            'id'       => 123,
            'image_id' => 456,
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertRegExp('/status:   OK/', $this->commandTester->getDisplay());
    }

    public function testExecuteCheckEventId()
    {
        $this->commandTester->execute(array(
            'command'  => $this->command->getName(),
            'id'       => 123,
            'image_id' => 456,
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertRegExp('/event_id: 1234/', $this->commandTester->getDisplay());
    }
}
