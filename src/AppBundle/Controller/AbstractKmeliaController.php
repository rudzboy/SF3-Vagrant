<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use AppBundle\Controller\Handler\SecurityResponseHandler;

abstract class AbstractKmeliaController extends Controller
{
    private
        $requestStack,
        $responseHandlers = array();
    
    public function __construct()
    {
        // loads default
        $this->loadDefaultResponseHandlers();
    }
    
    /**
     * inject the request_stack service
     * @param RequestStack $requestStack
     */
    protected function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    protected function loadDefaultResponseHandlers()
    {
        // security
        $this->addResponseHandler(new SecurityResponseHandler());
    }
    
    protected function addResponseHandler(Handler\ResponseHandler $responseHandler)
    {
        $this->responseHandlers[] = $responseHandler;
    }
    
    /**
     * Remove each handler which is same as the handler passed
     */
    protected function removeResponseHandlers(Handler\ResponseHandler $responseHandlerToRemove)
    {
        foreach ($this->responseHandlers as $index => $responseHandler) {
            if ($responseHandler instanceof $responseHandlerToRemove) {
                unset($this->responseHandlers[$index]);
            }
        }
    }
    
    /**
     * Renders a view with an handled Reponse
     * @see \Symfony\Bundle\FrameworkBundle\Controller\Controller::render()
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $response = parent::render($view, $parameters, $response);
        
        // get current request via the request_stack service
        $currentRequest = null;
        if ($this->requestStack instanceof RequestStack) {
            $currentRequest = $this->requestStack->getCurrentRequest();
        }
        
        foreach ($this->responseHandlers as $responseHandler) {
            if ($currentRequest !== null) {
                // give the current request to each response handler
                $responseHandler->setCurrentRequest($currentRequest);
            }
            
            $response = $responseHandler->handleResponse($response);
        }
        
        return $response;
    }
}
