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
        $faker = Faker\Factory::create('fr_FR');

        for($nbUsers = 1; $nbUsers <= 5; $nbUsers++){
            $user = new User();
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setEmail($faker->email);
            $user->setPseudo($faker->userName);
            if($nbUsers===1)
                $user->setRoles(['ROLE_ADMIN']);
            else
                $user->setRoles(['ROLE_USER']);
            $user->setActif($faker->boolean());
            $user->setTelephone('0298885566');
            $password = $this->hasher->hashPassword($user, 'azerty');
            $user->setPassword($password);
            $site = $this->getReference('site_' . $faker->numberBetween(1, 3));
            $user->setSite($site);
            $manager->persist($user);
            $this->setReference('user_'. $nbUsers,$user);
        }
        $manager->flush();
    }
}
