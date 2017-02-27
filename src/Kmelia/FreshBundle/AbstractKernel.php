<?php

namespace Kmelia\FreshBundle;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Config\EnvParametersResource;

abstract class AbstractKernel extends Kernel
{
    public function initializeWithoutCaching()
    {
        // init bundles
        $this->initializeBundles();
        
        // init container
        $this->initializeContainerWithoutCaching();
    }
    
    protected function initializeContainerWithoutCaching()
    {
        $container = $this->buildContainerWithoutCaching();
        
        // compiles the container.
        $container->getParameterBag()->resolve();
        
        $this->container = $container;
        $this->container->set('kernel', $this);
    }
    
    protected function buildContainerWithoutCaching()
    {
        $container = $this->getContainerBuilder();
        $container->addObjectResource($this);
        $this->prepareContainer($container);
        
        if (null !== $cont = $this->registerContainerConfiguration($this->getContainerLoader($container))) {
            $container->merge($cont);
        }
        
        $container->addResource(new EnvParametersResource('SYMFONY__'));
        
        return $container;
    }
}
