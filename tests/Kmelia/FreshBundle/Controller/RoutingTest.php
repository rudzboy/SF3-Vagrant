<?php

namespace Tests\Kmelia\FreshBundle\Controller;

use Tests\AppBundle\WebTestCase;

class RoutingTest extends WebTestCase
{
    /**
     * @dataProvider providerTestOk
     */
    public function testOk($route)
    {
        $client = $this->getClient();
        
        $client->request('GET', $route);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk(), 'Http code 200');
    }
    
    public function providerTestOk()
    {
        return array(
            array('/'),
            array('/no-http-cache'),
        );
    }
    
    /**
     * @dataProvider providerTestNotFound
     */
    public function testNotFound($route)
    {
        $client = $this->getClient();
        
        $client->request('GET', $route);
        $response = $client->getResponse();
        $this->assertTrue($response->isNotFound(), 'Http code 404');
    }
    
    public function providerTestNotFound()
    {
        return array(
            array('/not-found'),
            array('/deal-with-it'),
        );
    }
    
    /**
     * @group security
     * @dataProvider providerTestUnauthorized
     */
    public function testUnauthorized($route)
    {
        $client = $this->getClient();
        
        $client->request('GET', $route);
        $response = $client->getResponse();
        $this->assertSame(401, $response->getStatusCode(), 'Http code 401');
    }
    
    public function providerTestUnauthorized()
    {
        return array(
            array('/secured-area'),
        );
    }
}
