<?php

namespace AppBundle\Controller\Handler;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

interface ResponseHandler
{
    /**
     * @param Response $response
     * @return Response
     */
    public function handleResponse(Response $response);
    
    /**
     * @param Request $request
     */
    public function setCurrentRequest(Request $request);
}
