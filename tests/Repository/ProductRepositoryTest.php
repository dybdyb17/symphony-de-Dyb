<?php

namespace App\Tests\Repository;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    public function testFindByPaginate(): void
    {
        self::bootKernel();

        $productRepository = static::getContainer()->get(ProductRepository::class);
        $data = $productRepository->findPaginate();

        $this->assertEquals(10, count($data['products']));
    }
}
