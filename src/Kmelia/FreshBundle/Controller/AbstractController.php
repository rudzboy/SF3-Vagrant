<?php

namespace Kmelia\FreshBundle\Controller;

use AppBundle\Controller\AbstractKmeliaController;
use AppBundle\Controller\Handler\HttpCacheResponseHandler;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;

abstract class AbstractController extends AbstractKmeliaController
{
    private
        $httpCacheResponseHandler,
        $doctrineRepositories;
    
    public function __construct()
    {
        parent::__construct();
        
        // add http cache handler
        $this->addResponseHandler($this->getHttpCacheResponseHandler());
    }
    
    /**
     * @see \Symfony\Bundle\FrameworkBundle\Controller\Controller::render()
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        // inject the request_stack service
        $this->setRequestStack($this->container->get('request_stack'));
        
        return parent::render($view, $parameters, $response);
    }
    
    protected function getHttpCacheResponseHandler()
    {
        if (!$this->httpCacheResponseHandler instanceof HttpCacheResponseHandler) {
            $this->httpCacheResponseHandler = new HttpCacheResponseHandler();
        }
        
        return $this->httpCacheResponseHandler;
    }
    
    protected function getDoctrineRepository($name)
    {
        if (empty($this->doctrineRepositories[$name]) || !$this->doctrineRepositories[$name] instanceof EntityRepository) {
            $this->doctrineRepositories[$name] = $this->getDoctrine()->getRepository($name);
        }
        
        return $this->doctrineRepositories[$name];
    }
}
