<?php

namespace Tests\Kmelia\FreshBundle\Controller;

use Tests\AppBundle\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    /**
     * @dataProvider providerTestHomepage
     */
    public function testHomepage($route, $maxAge)
    {
        $client = $this->getClient();
        
        $crawler  = $client->request('GET', $route);
        $response = $client->getResponse();
        
        // content
        $this->assertGreaterThan(0, $crawler->filter('html:contains("fresh-symfony")')->count(), 'Word "fresh-symfony"');
        
        // headers
        $this->assertTrue($response->isOk(), 'Http code 200');
        $this->assertTrue($response->headers->contains('Content-Type', 'text/html; charset=UTF-8'), 'Html utf-8 page');
        $this->assertTrue($response->headers->hasCacheControlDirective('max-age'), 'Cache control max-age');
        $this->assertTrue($response->headers->hasCacheControlDirective('s-maxage'), 'Cache control s-maxage');
        $this->assertSame((string) $maxAge, $response->headers->getCacheControlDirective('max-age'), 'Invalid max-age');
        $this->assertTrue($response->headers->hasCacheControlDirective('must-revalidate'), 'Cache control must-revalidate');
        $this->assertTrue($response->headers->has('Expires'), 'Header expires');
        $this->assertTrue($response->headers->contains('X-Frame-Options', 'SAMEORIGIN'), 'Security header');
    }
    
    public function providerTestHomepage()
    {
        return array(
            array('/', 300),
            array('/routing-http-cache', 1),
            array('/specified-http-cache', 2),
            array('/specified-http-cache-and-routing-configuration', 2),
            array('/specified-http-cache-and-routing-configuration-override', 4),
        );
    }
    
    public function testNoHttpCacheHomepage()
    {
        $client = $this->getClient();
        
        $client->request('GET', '/no-http-cache');
        $response = $client->getResponse();
        
        // headers
        $this->assertTrue($response->isOk(), 'Http code 200');
        $this->assertFalse($response->isCacheable(), 'Response cacheable');
        $this->assertTrue($response->headers->contains('Cache-Control', 'no-cache'), 'Header cache control no-cache');
        $this->assertFalse($response->headers->has('Expires'), 'Header expires');
    }
}
