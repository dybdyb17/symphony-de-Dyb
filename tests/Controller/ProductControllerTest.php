<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\InMemoryUser;

class ProductControllerTest extends WebTestCase
{
    public function testCreateProduct(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/product/new');
        $buttonCrawlerNode = $crawler->selectButton('Enregistrer');

        $form = $buttonCrawlerNode->form();

        $form['product[title]'] = 'Fabien';
        $form['product[description]'] = 'Fabien';
        $form['product[price]'] = 125.26;
        $form['product[category]']->select('1');

        $client->submit($form);

        $this->assertResponseRedirects('/admin/product');
    }
}
