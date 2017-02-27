<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

abstract class AbstractKmeliaCommand extends ContainerAwareCommand
{
    const
        ERROR_EXIT_VALUE = 2;
    
    private
        $locking,
        $lockHandler;
    
    /**
     * Locking mechanism
     * @return \AppBundle\Command\AbstractKmeliaCommand
     */
    protected function enableLocking()
    {
        $this->locking = true;
        
        return $this;
    }
    
    protected function disableLocking()
    {
        $this->locking = false;
        
        return $this;
    }
    
    /**
     * Locking initialization
     * @see \Symfony\Component\Console\Command\Command::initialize()
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if ($this->isThatCommandLocked()) {
            $output->writeln(sprintf(
                '[Command <comment>%s</comment>] The locking is activated for this command and an instance is already launched.',
                $this->getName()
            ));
            
            $output->writeln(sprintf(
                '[Command <comment>%s</comment>] This runtime will not be started.',
                $this->getName()
            ));
            
            exit(self::ERROR_EXIT_VALUE);
        }
    }
    
    /**
     * @see http://symfony.com/doc/current/components/filesystem/lock_handler.html
     * No need to release the lock
     */
    private function isThatCommandLocked()
    {
        if ($this->locking !== true) {
            return false;
        }
        
        // Warning: do not use a local variable, the lock will be released immediately
        $this->lockHandler = new LockHandler($this->getName());
        return !$this->lockHandler->lock();
    }
}
