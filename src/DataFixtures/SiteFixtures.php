<?php

namespace App\DataFixtures;


use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbSite = 1; $nbSite <= 3; $nbSite++) {
            $site = new Site();
            $site->setNom($faker->city);
            $manager->persist($site);
            $this->addReference('site_'. $nbSite,$site);
        }
        $manager->flush();


    }
}
