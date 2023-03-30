<?php

namespace App\DataFixtures;

use App\Entity\Hangout;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserHangoutFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $hangouts = $manager->getRepository(Hangout::class)->findAll();
        foreach ($hangouts as $hangout){
            if($hangout instanceof Hangout){
                for($i = 0; $i < 3; $i++){
                    $hangout->addParticipant($users[rand(0,sizeof($users)-1)]);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [AppFixtures::class];
    }
}
