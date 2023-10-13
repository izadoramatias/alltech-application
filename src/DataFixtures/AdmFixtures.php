<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdmFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admPermission = new Permission();
        $admPermission->setName('ADM');

        $manager->persist($admPermission);
        $manager->flush();
    }
}

