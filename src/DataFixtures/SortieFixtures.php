<?php

namespace App\DataFixtures;


use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SortieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        for($nbSorties = 1; $nbSorties <= 5; $nbSorties++) {
            $sortie = new Sortie();
            $sortie->setNom($faker->title);
            $sortie->setDateHeureDebut($faker->dateTime);
            $sortie->setDateLimiteIncription($faker->dateTime);
            $sortie->setDuree($faker->numberBetween(1,5));
            $sortie->setInfoSortie($faker->realText(25));
            $sortie->setNbInscriptionsMax($faker->numberBetween(1,10));
            //$user = setArticle($this->getReference(Article::class.'_0'));
            $sortie->setOrganisateur($this->getReference('user_'. $faker->numberBetween(1, 5)));
            //$randomReferenceKey = sprintf('etat.%d', $this->$faker->numberBetween(1, 6));
            $etat = $this->getReference('etat.cree');
            $sortie->setEtat($etat);
            $lieu = $this->getReference('lieu_' . $faker->numberBetween(1, 5));
            $sortie->setLieu($lieu);
            $site = $this->getReference('site_' . $faker->numberBetween(1,3) );
            $sortie->setSite($site);
            $this->addReference('sortie_' . $nbSorties, $sortie);

            $manager->persist($sortie);

        }
        $manager->flush();
    }
}
