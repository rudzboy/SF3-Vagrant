<?php

use Kmelia\FreshBundle\AbstractKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends AbstractKernel
{
    public function registerBundles()
    {
        // symfony standard
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
        ];
        
        // src
        $bundles[] = new Kmelia\FreshBundle\KmeliaFreshBundle();
        
        // vendor
        //$bundles[] = new Vendor\VendorBundle\VendorVendorBundle();
        
        // development or integration
        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }
        
        return $bundles;
    }
    
    public function getRootDir()
    {
        return __DIR__;
    }
    
    public function getCacheDir()
    {
        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }
    
    public function getLogDir()
    {
        return dirname(__DIR__) . '/var/logs';
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(sprintf(
            '%s/config/config_%s.yml',
            $this->getRootDir(),
            $this->getEnvironment()
        ));
    }
}
