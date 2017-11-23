<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testShowproduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/{id}');
    }

    public function testGetlist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/_list-products/{max}');
    }

    public function testProduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product');
    }

    public function testListallproducts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listAllProducts');
    }

    public function testUpdateproduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/updateProduct');
    }

    public function testDeleteproduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deleteProduct');
    }

}
