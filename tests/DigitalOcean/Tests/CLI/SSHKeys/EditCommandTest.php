<?php

/**
 * This file is part of the DigitalOcean library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOcean\Tests\CLI\SSHKeys;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use DigitalOcean\Tests\TestCase;
use DigitalOcean\CLI\SSHKeys\EditCommand;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class EditCommandTest extends TestCase
{
    protected $application;
    protected $command;
    protected $commandTester;

    protected function setUp()
    {
        $this->application = new Application();

        $this->application->add(new EditCommand());

        $this->command = $this->application->find('ssh-keys:edit');

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
     * @expectedExceptionMessage Not implemented yet. Coming soon...
     */
    public function testExecuteNotImplementedYet()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'ssh_key_pub' => 'foobar',
        ));
    }
}
