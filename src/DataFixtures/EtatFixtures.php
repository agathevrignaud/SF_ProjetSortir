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
        $etats =[
            1=>[
                'libelle'=>'Créée'
            ],
            2=>[
                'libelle'=>'Ouverte'
            ],
            3=>[
                'libelle'=>'Clôturée'
            ],
            4=>[
                'libelle'=>'En cours'
            ],
            5=>[
                'libelle'=>'Passée'
            ],
            6=>[
                'libelle'=>'Annulée'
            ]
        ];

        foreach ($etats as $key => $value){
            $etat = new Etat();
            $etat->setLibelle($value['libelle']);
            $manager->persist($etat);
            $this->addReference('etat_' . $key,$etat);
        }
        $manager->flush();


    }
}
