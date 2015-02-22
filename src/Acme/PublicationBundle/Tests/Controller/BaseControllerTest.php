<?php

namespace Acme\PublicationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class BaseControllerTest extends WebTestCase
{
    protected $client = null;

    public function __construct() {
        $this->client = static::createClient();
    }

    public function SetUp() {
        $this->logIn('admin', 'test');
    }

    public function logIn($username, $password) {
       $crawler = $this->client->request('GET', '/login');
       $form = $crawler->selectButton('_submit')->form(array(
           '_username'  => $username,
           '_password'  => $password,
           ));
       $this->client->submit($form);

       $this->assertTrue($this->client->getResponse()->isRedirect());
       // $crawler = $this->client->followRedirect();
    }

}
