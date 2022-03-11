<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class LieuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbLieu = 0; $nbLieu < 10; $nbLieu++) {
            $lieu = new Lieu();
            $lieu->setNom($faker->sentence($nbWords = 3, $variableNbWords = true));
            $lieu->setRue($faker->streetName);
            $lieu->setLatitude($faker->latitude);
            $lieu->setLongitude($faker->longitude);
            $ville = $this->getReference('ville_' . $faker->numberBetween(1, 5));
            $lieu->setVille($ville);
            $manager->persist($lieu);
            $this->addReference('lieu_'. $nbLieu,$lieu);
        }

        $manager->flush();
    }
}
