<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstname);
            $user->setName($faker->name);
            $user->setMail($faker->mail);
            $password = $this->passwordEncoder->encodePassword($user, 'fake');
            $user->setPassword($password);
            $user->setRole(['ROLE_USER']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}