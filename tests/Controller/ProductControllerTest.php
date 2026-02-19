<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\InMemoryUser;

class ProductControllerTest extends WebTestCase
{
    private static ?int $id = null;

    public function testCreateProduct(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/product/new');
        $buttonCrawlerNode = $crawler->selectButton('Enregistrer');

        $form = $buttonCrawlerNode->form();
        $form['product[category]']->select('1');

        $client->submit($form, [
            'product[title]' => 'ace of diamond',
            'product[description]' => 'Voici la description de mon produit.',
            'product[price]' => 125.26
        ]);

        $container = self::getContainer();
        $product = $container->get(ProductRepository::class)->findOneBy(['title' => 'ace of diamond']);
        self::$id = $product->getId();

        $this->assertResponseRedirects('/admin/product');
    }

    public function testEditProduct(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/product/' . self::$id . '/edit');
        $buttonCrawlerNode = $crawler->selectButton('Enregistrer');

        $form = $buttonCrawlerNode->form();
        $form['product[category]']->select('1');

        $client->submit($form, [
            'product[title]' => 'ace of diamond modifié',
            'product[description]' => 'Voici la description de mon produit.',
            'product[price]' => 125.26
        ]);

        $this->assertResponseRedirects('/admin/product');
    }
}
