<?php

namespace App\Tests\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\InMemoryUser;

class CategoryControllerTest extends WebTestCase
{
    private static ?int $id = null;

    public function testPageCategoryIndex(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $client->request('GET', '/admin/category');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateCategory(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/category/new');
        $form = $crawler->selectButton('Save')->form();

        $form['category[title]'] = 'Ma nouvelle catégorie';

        $client->submit($form);

        $container = self::getContainer();
        $category = $container->get(CategoryRepository::class)->findOneBy(['title' => 'Ma nouvelle catégorie']);
        self::$id = $category->getId();

        $this->assertResponseRedirects('/admin/category');
    }

    public function testEditCategory(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/category/' . self::$id . '/edit');
        $form = $crawler->selectButton('Update')->form();

        $form['category[title]'] = 'Ma catégorie modifiée';

        $client->submit($form);

        $this->assertResponseRedirects('/admin/category');
    }

    public function testShowCategory(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $client->request('GET', '/admin/category/' . self::$id);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
