<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbVille = 1; $nbVille <= 5; $nbVille++) {
            $ville = new Ville();
            $ville->setNom($faker->city);
            $ville->setCodePostal($faker->numberBetween(10000, 99999));
            $manager->persist($ville);
            $this->addReference('ville_'.$nbVille,$ville);
        }
        $manager->flush();
    }
}
