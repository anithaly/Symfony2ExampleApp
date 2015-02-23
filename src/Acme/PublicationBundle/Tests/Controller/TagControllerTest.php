<?php

namespace Acme\PublicationBundle\Tests\Controller;

class TagControllerTest extends BaseControllerTest
{

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/tag/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $heading = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals('Tags list', $heading);
    }

    public function testCompleteScenario()
    {
        // Create a new entry in the database
        $crawler = $this->client->request('GET', '/tag/');
        // var_dump($crawler);exit;
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /tag/");
        $crawler = $this->client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Save')->form(array(
            'publication_tag[name]'  => 'Tag 1',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Tag 1")')->count(), 'Missing element td:contains("Tag 1")');

        // Assert that the response is a redirect to /demo/contact
        // $this->assertTrue(
            // $client->getResponse()->isRedirect('/tag/x')
        // );

        //  back to list
        $crawler = $this->client->click($crawler->selectLink('Tags')->link());

        // Edit the entity
        $crawler = $this->client->click($crawler->selectLink('edit')->link());

        // $link = $crawler->filter('a:contains("edit")')->eq(1)->link();
        // $crawler = $client->click($link);

        $form = $crawler->selectButton('Save')->form(array(
            'publication_tag[name]'  => 'Foo',
            // ... other fields to fill
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"

        // var_dump($crawler->filter('table tbody td'));
        // $this->assertEquals('Foo', $table);


        // $nCrawler = $crawler->filter('')
        // ->last()
        // ->parents()
        // ->first();

        //  back to list
        $crawler = $this->client->click($crawler->selectLink('Tags')->link());
        // Edit the entity
        $crawler = $this->client->click($crawler->selectLink('show')->link());
        // Delete the entity
        $this->client->submit($crawler->selectButton('Delete')->form());
        $crawler = $this->client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $this->client->getResponse()->getContent());
    }


}
