<?php

namespace AppBundle\Controller\Handler;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractResponseHandler
{
    protected
        $request;
    
    public function setCurrentRequest(Request $request)
    {
        $this->request = $request;
    }
}
