<?php

namespace Tests\Kmelia\FreshBundle\Command;

use Tests\AppBundle\WebTestCase;
use Kmelia\FreshBundle\Command\Test\SleeperCommand;
use Symfony\Component\Process\Process;

class LockingTest extends WebTestCase
{
    /**
     * @group console
     */
    public function testSleeperCommand()
    {
        // init
        $sleeperCommand = new SleeperCommand();
        $sleeperCommandLockFilePath = sprintf(
            '%s/lock_command_%s',
            $this->getClient()->getKernel()->getCacheDir(),
            str_replace(':', '_', $sleeperCommand->getName())
        );
        $commandline = sprintf(
            'env bin/console --env=%s %s',
            $this->getClient()->getKernel()->getEnvironment(),
            $sleeperCommand->getName()
        );
        
        // the first run of this command with the locking mechanism: the lock is created
        $firstProcess = new Process($commandline);
        $firstProcess->start();
        sleep(SleeperCommand::SLEEPING_TIME / 2);
        
        // the second run of this command is invalid
        $secondProcess = new Process($commandline);
        $secondProcess->run();
        $this->assertSame(2, $secondProcess->getExitCode(), 'Invalid exit code');
        $secondProcessOutput = $secondProcess->getOutput();
        $this->assertSame(2, substr_count($secondProcessOutput, PHP_EOL), 'There is more than two lines');
        $this->assertContains('locking is activated', $secondProcessOutput, 'Incorrect line 1');
        $this->assertContains('will not be started', $secondProcessOutput, 'Incorrect line 2');
        
        // check the first process is still running
        $this->assertTrue($firstProcess->isRunning(), sprintf('The command %s does not work', $firstProcess->getCommandLine()));
        
        // after the sleeping, the lock is released
        sleep(1 + SleeperCommand::SLEEPING_TIME / 2);
        $this->assertSame(0, $firstProcess->getExitCode());
        $firstProcessOutput = $firstProcess->getOutput();
        $this->assertSame(3, substr_count($firstProcessOutput, PHP_EOL), 'There is more than three lines');
        $this->assertContains('starting', $firstProcessOutput);
        $this->assertContains('processing', $firstProcessOutput);
        $this->assertContains('ending', $firstProcessOutput);
        
        // the third run of this command, after the sleeping, is valid
        $thirdProcess = new Process($commandline);
        $thirdProcess->run();
        $this->assertSame(0, $thirdProcess->getExitCode());
    }
}
