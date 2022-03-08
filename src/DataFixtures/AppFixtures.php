<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EtatFixtures::class,
            VilleFixtures::class,
            LieuFixtures::class,
            SiteFixtures::class,
            UserFixtures::class,
            SortieFixtures::class,
        ];
    }
}
