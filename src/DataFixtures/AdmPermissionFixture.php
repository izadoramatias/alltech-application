<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdmPermissionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admPermission = new Permission();
        $admPermission->setName('ADM');
        $admPermission->setId(3);

        $manager->persist($admPermission);
        $manager->flush();
    }
}

