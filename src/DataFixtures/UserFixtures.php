<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
    }

    public function load(ObjectManager $manager): void
    {
         $admin = new User();
            $admin->setEmail('quai.antiquead@gmail.com');
            $admin->setResetToken('admin');
            $admin->setUsername('Admin');
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'admin'));
            $manager->persist($admin);
            $manager->flush();
    }
         
           
}