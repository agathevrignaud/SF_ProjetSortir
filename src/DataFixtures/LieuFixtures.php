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
        $lieux =[
            1=>[
                'nom'=>'Bowling',
                'rue'=>'2 rue du Général de Gaulle',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 1)
            ],
            2=>[
                'nom'=>'Cinéma',
                'rue'=>'4, place de Renaud',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 1)
            ],
            3=>[
                'nom'=>'Piscine',
                'rue'=>'71, impasse Antoinette Chauveau',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 1)
            ],
            4=>[
                'nom'=>'plage du Menhir',
                'rue'=>'68, rue de Rolland',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 2)
            ],
            5=>[
                'nom'=>'Parc de longchamps',
                'rue'=>'6, rue de Legrand',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 2)
            ],
            6=>[
                'nom'=>'Café des chats',
                'rue'=>'62, avenue Maurice Gregoire',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 2)
            ],
            7=>[
                'nom'=>'café du port',
                'rue'=>'538, place Royer',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 3)
            ],
            8=>[
                'nom'=>'Le mail',
                'rue'=>'chemin Virginie Lopes',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 3)
            ],
            9=>[
                'nom'=>'Boite de nuit',
                'rue'=>'39, rue Antoine Renaud',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 4)
            ],
            10=>[
                'nom'=>'Piscine',
                'rue'=>'68, boulevard Lecoq',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 4)
            ],
            11=>[
                'nom'=>'Bowling',
                'rue'=>'78, boulevard de Breton',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 5)
            ],
            12=>[
                'nom'=>'Salle de spectacle',
                'rue'=>'70, chemin Meyer',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 5)
            ],
            13=>[
                'nom'=>'plage de minou',
                'rue'=>'6, rue Hoarau',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 6)
            ],
            14=>[
                'nom'=>'café des vieux',
                'rue'=>'boulevard Lemoine',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 6)
            ],
            15=>[
                'nom'=>'Musée du centre ville',
                'rue'=>'719, impasse Jean Chauvin',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 7)
            ],
            16=>[
                'nom'=>'salle polyvalente',
                'rue'=>'992, avenue Wagner',
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'ville'=>$this->getReference('ville_' . 7)
            ]

        ];

        foreach ($lieux as $key => $value){
            $lieu = new Lieu();
            $lieu->setNom($value['nom']);
            $lieu->setRue($value['rue']);
            $lieu->setLatitude($value['latitude']);
            $lieu->setLongitude($value['longitude']);
            $lieu->setVille($value['ville']);
            $manager->persist($lieu);
            $this->addReference('lieu_' . $key,$lieu);
        }
        $manager->flush();

    }
}
