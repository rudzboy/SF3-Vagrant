<?php

namespace Tests\AppBundle\Kernel;

use Tests\AppBundle\WebTestCase;

class FrameworkTest extends WebTestCase
{
    public function testDirectories()
    {
        $client = $this->getClient();
        
        $appDirectory   = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'app';
        $logsDirectory  = dirname($appDirectory) . '/var/logs';
        $cacheDirectory = dirname($appDirectory) . '/var/cache';
        
        if ($client->getKernel() instanceof \MicroframeworkAppKernel) {
            // specificity on microframework
            $cacheDirectory .= '/microframeworktest';
        } else {
            // on framework
            $cacheDirectory .= '/test';
        }
        
        $this->assertSame($appDirectory, $client->getKernel()->getRootDir());
        $this->assertSame($logsDirectory, $client->getKernel()->getLogDir());
        $this->assertSame($cacheDirectory, $client->getKernel()->getCacheDir());
    }
}
