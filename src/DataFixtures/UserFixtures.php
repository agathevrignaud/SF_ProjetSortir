<?php

namespace App\DataFixtures;



use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $users =[
            1=>[
                'nom'=>'Admin',
                'prenom'=>'General',
                'pseudo'=>'adminSortir',
                'email'=>'sortir@no-reply.fr',
                'telephone'=>null,
                'role'=>['ROLE_ADMIN'],
                'actif'=>true,
                'site'=>$this->getReference('site_' . 1)
            ],
            2=>[
                'nom'=>'Lagarde',
                'prenom'=>'Yves',
                'pseudo'=>'Yvon123',
                'email'=>'yves.lagarde@gmail.com',
                'telephone'=>null,
                'role'=>['ROLE_USER'],
                'actif'=>false,
                'site'=>$this->getReference('site_' . 1)
            ],
            3=>[
                'nom'=>'Le Gall',
                'prenom'=>'Nathalie',
                'pseudo'=>'Nat',
                'email'=>'nathalielegall@yaho.fr',
                'telephone'=>'0190884287',
                'role'=>['ROLE_USER'],
                'actif'=>true,
                'site'=>$this->getReference('site_' . 2)
            ],
            4=>[
                'nom'=>'Vallee',
                'prenom'=>'Amélie',
                'pseudo'=>'Amelie85',
                'email'=>'amelie.vallee@orange.fr',
                'telephone'=>'0734337751',
                'role'=>['ROLE_USER'],
                'actif'=>true,
                'site'=>$this->getReference('site_' . 2)
            ],
            5=>[
                'nom'=>'François',
                'prenom'=>'Germain',
                'pseudo'=>'germaim78',
                'email'=>'francois.germain@wanadoo.f',
                'telephone'=>'0940520492',
                'role'=>['ROLE_USER'],
                'actif'=>true,
                'site'=>$this->getReference('site_' . 3)
            ],
            6=>[
                'nom'=>'Faivre',
                'prenom'=>'Léon',
                'pseudo'=>'Leon le lion',
                'email'=>'leon.faivre@bouygtel.fr',
                'telephone'=>'0682127279',
                'role'=>['ROLE_USER'],
                'actif'=>true,
                'site'=>$this->getReference('site_' . 3)
            ],
            7=>[
                'nom'=>'Blot',
                'prenom'=>'Andrée',
                'pseudo'=>'Dédé le cochon',
                'email'=>'andree.blot@orange.fr',
                'telephone'=>'0580057636',
                'role'=>['ROLE_USER'],
                'actif'=>false,
                'site'=>$this->getReference('site_' . 4)
            ],
            8=>[
                'nom'=>'Breton',
                'prenom'=>'Agnès',
                'pseudo'=>'la petite bretonne ',
                'email'=>'agnes.breton@yahoo.fr',
                'telephone'=>'0387335827',
                'role'=>['ROLE_USER'],
                'actif'=>true,
                'site'=>$this->getReference('site_' . 4)
            ],
            9=>[
                'nom'=>'Lefevre',
                'prenom'=>'Édouard',
                'pseudo'=>'Eddy',
                'email'=>'edouard.lefevre@orange.fr',
                'telephone'=>'0108530198',
                'role'=>['ROLE_USER'],
                'actif'=>false,
                'site'=>$this->getReference('site_' . 5)
            ],
            10=>[
                'nom'=>'Roux',
                'prenom'=>'Laurent',
                'pseudo'=>'le petit roux',
                'email'=>'laurent.roux@laposte.fr',
                'telephone'=>'0486096306',
                'role'=>['ROLE_USER'],
                'actif'=>true,
                'site'=>$this->getReference('site_' . 5)
            ]
        ];
        foreach ($users as $key => $value){
            $user = new User();
            $user->setNom($value['nom']);
            $user->setPrenom($value['prenom']);
            $user->setEmail($value['email']);
            $user->setPseudo($value['pseudo']);
            $user->setRoles($value['role']);
            $user->setActif($value['actif']);
            $user->setTelephone($value['telephone']);
            $password = $this->hasher->hashPassword($user, 'azerty');
            $user->setPassword($password);
            $user->setSite($value['site']);
            $manager->persist($user);
            $this->setReference('user_'. $key,$user);
        }
        $manager->flush();
    }
}
