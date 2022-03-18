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
        $sorties =[
            1=>[
                'nom'=>'Aller à la plage',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 2),
                'Etat'=>$this->getReference('etat_' . 1),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 1),
                'Lieu'=>$this->getReference('lieu_' . 4)
            ],
            2=>[
                'nom'=>'Journée au SPA',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 2),
                'Etat'=>$this->getReference('etat_' . 2),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 1),
                'Lieu'=>$this->getReference('lieu_' . 3)
            ],
            3=>[
                'nom'=>'Promener les chien',
                'DateHeureDebut'=>$faker->dateTime('2022/03/20',null),
                'DateLimiteIncription'=>$faker->dateTime('2022/03/16',null),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 3),
                'Etat'=>$this->getReference('etat_' . 3),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 2),
                'Lieu'=>$this->getReference('lieu_' . 4)
            ],
            4=>[
                'nom'=>'Courir au parc',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 3),
                'Etat'=>$this->getReference('etat_' . 2),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 2),
                'Lieu'=>$this->getReference('lieu_' . 5)
            ],
            5=>[
                'nom'=>'Café commère',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 4),
                'Etat'=>$this->getReference('etat_' . 4),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 2),
                'Lieu'=>$this->getReference('lieu_' . 6)
            ],
            6=>[
                'nom'=>'Paddle',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 4),
                'Etat'=>$this->getReference('etat_' . 6),
                'motifAnnulation'=>$faker->word,
                'Site'=>$this->getReference('site_' . 2),
                'Lieu'=>$this->getReference('lieu_' . 4)
            ],
            7=>[
                'nom'=>'Courir au bord de mer',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 5),
                'Etat'=>$this->getReference('etat_' . 1),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 3),
                'Lieu'=>$this->getReference('lieu_' . 4)
            ],
            8=>[
                'nom'=>'Pique nique',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 5),
                'Etat'=>$this->getReference('etat_' . 6),
                'motifAnnulation'=>$faker->word,
                'Site'=>$this->getReference('site_' . 3),
                'Lieu'=>$this->getReference('lieu_' . 1)
            ],
            9=>[
                'nom'=>'Nouveau Batman',
                'DateHeureDebut'=>$faker->dateTime('2022/02/09',null),
                'DateLimiteIncription'=>$faker->dateTime('2022/03/16',null),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 6),
                'Etat'=>$this->getReference('etat_' . 5),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 3),
                'Lieu'=>$this->getReference('lieu_' . 2)
            ],
            10=>[
                'nom'=>'Un verre au bar',
                'DateHeureDebut'=>$faker->dateTime('2022/03/26',null),
                'DateLimiteIncription'=>$faker->dateTime('2022/03/16',null),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 6),
                'Etat'=>$this->getReference('etat_' . 3),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 3),
                'Lieu'=>$this->getReference('lieu_' . 7)
            ],
            11=>[
                'nom'=>'Balade en vélo',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 7),
                'Etat'=>$this->getReference('etat_' . 1),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 4),
                'Lieu'=>$this->getReference('lieu_' . 8)
            ],
            12=>[
                'nom'=>'Sortie en boite de nuit',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 7),
                'Etat'=>$this->getReference('etat_' . 2),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 4),
                'Lieu'=>$this->getReference('lieu_' . 9)
            ],
            13=>[
                'nom'=>'Bowling',
                'DateHeureDebut'=>$faker->dateTime('2022/03/17', null),
                'DateLimiteIncription'=>$faker->dateTime('2022/03/16',null),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 8),
                'Etat'=>$this->getReference('etat_' . 5),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 4),
                'Lieu'=>$this->getReference('lieu_' . 11)
            ],
            14=>[
                'nom'=>'Concert de Vitalic',
                'DateHeureDebut'=>$faker->dateTime('now', null),
                'DateLimiteIncription'=>$faker->dateTime('now', null),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 8),
                'Etat'=>$this->getReference('etat_' . 4),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 4),
                'Lieu'=>$this->getReference('lieu_' . 12)
            ],
            15=>[
                'nom'=>'Kayak',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 9),
                'Etat'=>$this->getReference('etat_' . 6),
                'motifAnnulation'=>$faker->word,
                'Site'=>$this->getReference('site_' . 5),
                'Lieu'=>$this->getReference('lieu_' . 13)
            ],
            16=>[
                'nom'=>'Brocante',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 9),
                'Etat'=>$this->getReference('etat_' . 2),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 5),
                'Lieu'=>$this->getReference('lieu_' . 14)
            ],
            17=>[
                'nom'=>'Visite au musée',
                'DateHeureDebut'=>$faker->dateTime('now',null),
                'DateLimiteIncription'=>$faker->dateTime('now', null),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 10),
                'Etat'=>$this->getReference('etat_' . 4),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 5),
                'Lieu'=>$this->getReference('lieu_' . 15)
            ],
            18=>[
                'nom'=>'Atelier récup',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 10),
                'Etat'=>$this->getReference('etat_' . 1),
                'motifAnnulation'=>null,
                'Site'=>$this->getReference('site_' . 5),
                'Lieu'=>$this->getReference('lieu_' . 16)
            ],
            19=>[
                'nom'=>'randonnée',
                'DateHeureDebut'=>$faker->dateTimeBetween('now', '+2weeks'),
                'DateLimiteIncription'=>$faker->dateTimeBetween('+3weeks', '+6weeks'),
                'Duree'=>$faker->numberBetween(10,240),
                'NbInscriptionsMax'=>$faker->numberBetween(1,10),
                'InfoSortie'=>$faker->realText(25),
                'Organisateur'=>$this->getReference('user_'. 10),
                'Etat'=>$this->getReference('etat_' . 6),
                'motifAnnulation'=>$faker->word,
                'Site'=>$this->getReference('site_' . 5),
                'Lieu'=>$this->getReference('lieu_' . 13)
            ]
        ];
        foreach ($sorties as $key => $value){
            $sortie = new Sortie();
            $sortie->setNom($value['nom']);
            $sortie->setDateHeureDebut($value['DateHeureDebut']);
            $sortie->setDateLimiteIncription($value['DateLimiteIncription']);
            $sortie->setDuree($value['Duree']);
            $sortie->setInfoSortie($value['InfoSortie']);
            $sortie->setNbInscriptionsMax($value['NbInscriptionsMax']);
            $sortie->setOrganisateur($value['Organisateur']);
            $sortie->setEtat($value['Etat']);
            $sortie->setMotifAnnulation($value['motifAnnulation']);
            $sortie->setSite($value['Site']);
            $sortie->setLieu($value['Lieu']);
            $manager->persist($sortie);
            $this->setReference('sortie_'. $key,$sortie);
        }
        $manager->flush();
    }
}
