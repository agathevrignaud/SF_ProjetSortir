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
        $sites =[
            1=>[
                'nom'=>'Nantes'
            ],
            2=>[
                'nom'=>'Rennes'
            ],
            3=>[
                'nom'=>'Niort'
            ],
            4=>[
                'nom'=>'Quimper'
            ],
            5=>[
                'nom'=>'Saint-Herblain'
            ]
        ];

        foreach ($sites as $key => $value){
            $site = new Site();
            $site->setNom($value['nom']);
            $manager->persist($site);
            $this->addReference('site_' . $key,$site);
        }
        $manager->flush();
    }
}
