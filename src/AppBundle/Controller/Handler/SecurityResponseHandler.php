<?php

namespace AppBundle\Controller\Handler;

use Symfony\Component\HttpFoundation\Response;

class SecurityResponseHandler extends AbstractResponseHandler implements ResponseHandler
{
    /**
     * Add security headers response
     */
    public function handleResponse(Response $response)
    {
        $headers = array(
            // http://blogs.msdn.com/b/ieinternals/archive/2010/03/30/combating-clickjacking-with-x-frame-options.aspx
            'X-Frame-Options' => 'SAMEORIGIN',
            // https://www.veracode.com/blog/2014/03/guidelines-for-setting-security-headers/
            'X-XSS-Protection' => '1; mode=block',
            // http://msdn.microsoft.com/en-us/library/ie/gg622941%28v=vs.85%29.aspx
            'X-Content-Type-Options' => 'nosniff',
        );
        
        foreach ($headers as $key => $values) {
            $response->headers->set($key, $values);
        }
        
        return $response;
    }
}
