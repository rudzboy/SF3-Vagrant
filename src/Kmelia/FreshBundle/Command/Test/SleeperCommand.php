<?php

namespace Kmelia\FreshBundle\Command\Test;

use AppBundle\Command\AbstractKmeliaCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SleeperCommand extends AbstractKmeliaCommand
{
    const
        SLEEPING_TIME = 2;
    
    protected function configure()
    {
        // enable the locking mechanism
        $this->enableLocking();
        
        $this
            ->setName('kmelia:test:sleeper')
            ->setDescription(sprintf(
                'This command sleeps for %d seconds.',
                self::SLEEPING_TIME
            ));
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('starting ...');
        
        sleep(self::SLEEPING_TIME / 2);
        
        $output->writeln('... processing ...');
        
        sleep(self::SLEEPING_TIME / 2);
        
        $output->writeln('... ending');
    }
}
