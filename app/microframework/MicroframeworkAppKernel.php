<?php

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollectionBuilder;

class MicroframeworkAppKernel extends AppKernel
{
    use MicroKernelTrait;
    
    public function registerBundles()
    {
        // symfony standard
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
        );
        
        // src
        $bundles[] = new Kmelia\FreshBundle\KmeliaFreshBundle();
        
        // development or integration
        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }
        
        return $bundles;
    }
    
    public function getCacheDir()
    {
        return dirname(dirname(__DIR__)) . '/var/cache/microframework' . $this->getEnvironment();
    }
    
    protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader)
    {
        $loader->load(sprintf(
            '%s/config/config_%s.yml',
            __DIR__,
            $this->getEnvironment()
        ));
    }
    
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        // import the WebProfilerRoutes, only if the bundle is enabled
        if (isset($this->bundles['WebProfilerBundle'])) {
            $routes->mount('/_wdt', $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.xml'));
            $routes->mount('/_profiler', $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml'));
        }
        
        // load the routes
        $routes->mount('/', $routes->import('@KmeliaFreshBundle/Resources/config/routing.yml'));
    }
}
