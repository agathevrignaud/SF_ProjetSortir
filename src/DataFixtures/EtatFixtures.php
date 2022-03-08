<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $etat1 = new Etat();
        $etat1->setLibelle('Créée');
        $manager->persist($etat1);
        $this->addReference('etat.cree',$etat1);

        $etat2 = new Etat();
        $etat2->setLibelle('Ouverte');
        $manager->persist($etat2);
        $this->addReference('etat.ouverte',$etat2);

        $etat3 = new Etat();
        $etat3->setLibelle('Clôturée');
        $manager->persist($etat3);
        $this->addReference('etat.cloturee',$etat3);

        $etat4 = new Etat();
        $etat4->setLibelle('Activité en cours');
        $manager->persist($etat4);
        $this->addReference('etat.activite',$etat4);

        $etat5 = new Etat();
        $etat5->setLibelle('Passée');
        $manager->persist($etat5);
        $this->addReference('etat.passee',$etat5);

        $etat6 = new Etat();
        $etat6->setLibelle('Annulée');
        $manager->persist($etat6);
        $this->addReference('etat.annulee',$etat6);

        $manager->flush();

    }
}
