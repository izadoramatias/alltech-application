<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\PermissionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private PermissionRepository $repository
    ){}

    public function load(ObjectManager $manager): void
    {
        $admLogin = $_ENV['ADM_LOGIN'];
        $admPassword = $_ENV['ADM_PASSWORD'];

        $admPermission = $this->repository->findOneBy(['name' => 'ADM']);

        $user = new User();
        $user->setEmail($admLogin);

        $hashedPassword = $this->hasher->hashPassword($user, $admPassword);
        $user->setPassword($hashedPassword);
        $user->setFullName('Adm Operator');
        $user->setPhone('1234');
        $user->setPermission($admPermission);

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AdmFixtures::class
        ];
    }
}
