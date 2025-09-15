<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaginationTest extends WebTestCase
{
    public function testHomePagePagination()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();
        
        // Check that pagination links are present
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.join')->count());
        
        // Check that we have posts displayed
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.article-card')->count());
    }
    
    public function testCategoryPagePagination()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/category/technologie');
        
        $this->assertResponseIsSuccessful();
        
        // Check that pagination links are present
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.join')->count());
        
        // Check that we have posts displayed
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.article-card')->count());
    }
    
    public function testSearchPagePagination()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search?q=et');
        
        $this->assertResponseIsSuccessful();
        
        // Check that pagination links are present
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.join')->count());
        
        // Check that we have posts displayed
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.card')->count());
    }
    
    public function testHomePageWithPageParameter()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/?page=2');
        
        $this->assertResponseIsSuccessful();
        
        // Check that the active page is correctly highlighted
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.btn-active')->count());
    }
    
    public function testCategoryPageWithPageParameter()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/category/technologie?page=2');
        
        $this->assertResponseIsSuccessful();
        
        // Check that the active page is correctly highlighted
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.btn-active')->count());
    }
    
    public function testSearchPageWithPageParameter()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search?q=et&page=2');
        
        $this->assertResponseIsSuccessful();
        
        // Check that the active page is correctly highlighted
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.btn-active')->count());
    }
}