<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Symfony\Component\Filesystem\Filesystem;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $faker->addProvider(new FakerPicsumImagesProvider($faker));

        $filesystem = new Filesystem();
        $destDir = dirname(__DIR__) . '/../public/uploads/products';

        if (!$filesystem->exists($destDir)) {
            $filesystem->mkdir($destDir, 0775);
        }

        for ($i = 1; $i <= 20; $i++) {
            $product = new Product();

            $filePath = $faker->image(dir: '/tmp', width: 640, height: 480);

            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            $filename = uniqid('products_', true) . '.' . $ext;

            $filesystem->copy($filePath, $destDir . '/' . $filename);

            $filesystem->remove($filePath);

            $product->setTitle($faker->words(3, true))
                ->setImageFilename($filename)
                ->setPrice($faker->numberBetween($min = 50, $max = 300))
                ->setDescription($faker->realText($maxNbChars = 200, $indexSize = 2))
                ->setCategory($this->getReference('category-' . rand(0, 5), Category::class))
            ;

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
