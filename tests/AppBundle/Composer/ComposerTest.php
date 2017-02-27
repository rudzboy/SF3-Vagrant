<?php

namespace Tests\AppBundle\Composer;

class ComposerTest extends \PHPUnit_Framework_TestCase
{
    public function testAutoloadFile()
    {
        $this->assertFileExists('vendor/autoload.php');
    }
    
    public function testVendorBinFile()
    {
        $this->assertFileNotExists('bin/phpunit');
        $this->assertFileExists('vendor/bin/phpunit');
    }
}
