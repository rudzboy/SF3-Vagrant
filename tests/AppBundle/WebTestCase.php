<?php

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class WebTestCase extends BaseWebTestCase
{
    private
        $client;
    
    protected function getClient()
    {
        if (!$this->client instanceof Client) {
            $this->overrideKernelDirectory();
            $this->client = static::createClient();
            
            $this->writeDebug('Creating a new Symfony\Bundle\FrameworkBundle\Client');
        }
        
        return $this->client;
    }
    
    private function overrideKernelDirectory()
    {
        if (getenv('PHPUNIT_SYMFONY_KERNEL_DIRECTORY') !== false) {
            $_SERVER['KERNEL_DIR'] = getenv('PHPUNIT_SYMFONY_KERNEL_DIRECTORY');
            
            $this->writeDebug(sprintf(
                'Overriding the PHP server variable KERNEL_DIR to %s',
                $_SERVER['KERNEL_DIR']
            ));
        }
    }
    
    protected function writeDebug($message)
    {
        if ($this->isDebuggingEnabled()) {
            echo PHP_EOL . $message;
        }
    }
    
    protected function isDebuggingEnabled()
    {
        return $this->isParameterEnabled('--debug');
    }
    
    private function isParameterEnabled($parameter)
    {
        if (empty($_SERVER['argv'])) {
            return false;
        }
        
        if (!in_array($parameter, $_SERVER['argv'])) {
            return false;
        }
        
        return true;
    }
}
