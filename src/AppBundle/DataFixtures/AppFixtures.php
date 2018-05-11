<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Product;
use AppBundle\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        $spices = [
            ['name'=>'Ail', 'media'=>'medias/ail.jpg'],
            ['name'=>'Basilic', 'media'=>'medias/basilic.jpg'],
            ['name'=>'Ciboulette', 'media'=>'medias/ciboulette.jpg'],
            ['name'=>'Herbes de provence', 'media'=>'medias/herbes-provence.jpg'],
            ['name'=>'Origan', 'media'=>'medias/origan.jpg'],
            ['name'=>'Persil', 'media'=>'medias/persil.jpg'],
            ['name'=>'Romarin', 'media'=>'medias/romarin.jpg'],
            ['name'=>'Thym', 'media'=>'medias/thym.jpg'],
        ];

        foreach ($spices as $s) {
            $product = new Product();
            $media = new Media();

            $media->setLocation($s['media']);
            $media->setProduct($product);
            $manager->persist($media);

            $product->setName($s['name'])
                ->setDescription('')
                ->setPrice(mt_rand(0, 50))
                ->addMedia($media);
            $manager->persist($product);
        }

        $manager->flush();
    }
}