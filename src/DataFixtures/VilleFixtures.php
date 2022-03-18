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
        $villes =[
            1=>[
                'nom'=>'Rennes',
                'codePostal'=>'35000'
            ],
            2=>[
                'nom'=>'Angers',
                'codePostal'=>'49100'
            ],
            3=>[
                'nom'=>'Avignon',
                'codePostal'=>'84000'
            ],
            4=>[
                'nom'=>'Noyal-sur-Vilaine',
                'codePostal'=>'35530'
            ],
            5=>[
                'nom'=>'Redon',
                'codePostal'=>'35600'
            ],
            6=>[
                'nom'=>'Chantepie',
                'codePostal'=>'35135'
            ],
            7=>[
                'nom'=>'Brest',
                'codePostal'=>'29200'
            ]
        ];

        foreach ($villes as $key => $value){
            $ville = new Ville();
            $ville->setNom($value['nom']);
            $ville->setCodePostal($value['codePostal']);
            $manager->persist($ville);
            $this->addReference('ville_' . $key,$ville);
        }
        $manager->flush();
    }
}
