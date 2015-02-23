<?php

namespace Acme\LogEntryBundle\Tests\Controller;

use Acme\PublicationBundle\Tests\Controller\BaseControllerTest;

class LogControllerTest extends BaseControllerTest
{
    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/log/');

        $heading = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals('Log list', $heading);
    }
}
