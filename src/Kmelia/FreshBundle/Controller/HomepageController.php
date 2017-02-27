<?php

namespace Kmelia\FreshBundle\Controller;

use AppBundle\Controller\Handler\HttpCacheResponseHandler;

class HomepageController extends AbstractController
{
    public function defaultHttpCacheAction()
    {
        return $this->render('KmeliaFreshBundle:Homepage:home.html.twig');
    }
    
    public function specifiedHttpCacheAction()
    {
        $this->getHttpCacheResponseHandler()->setDuration(2);
        
        return $this->render('KmeliaFreshBundle:Homepage:home.html.twig');
    }
    
    public function noHttpCacheHomepageAction()
    {
        // remove http cache handler
        $this->removeResponseHandlers(new HttpCacheResponseHandler());
        
        return $this->render('KmeliaFreshBundle:Homepage:home.html.twig');
    }
}
