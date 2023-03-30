<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Hangout;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\State;
use App\Entity\User;
use App\Repository\HangoutRepository;
use App\TypeConstraints\StateConstraints;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;}


    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $manager);

        // $product = new Product();
        // $manager->persist($product);
        $nantes = new Site();
        $nantes->setName('Nantes');
        $manager->persist($nantes);
        $rennes = new Site();
        $rennes->setName('Rennes');
        $manager->persist($rennes);
        $manager->flush();
        $populator->addEntity(City::class, 10, ['name'=>function() use ($generator){ return $generator->city();}]);
        $populator->execute();
        $cities = $manager->getRepository(City::class)->findAll();
        $populator->addEntity(Place::class, 50,[], [
            function(Place $place) use ($cities){
                $place->setCity($cities[rand(0,9)]);
            }
        ]);
        $user = new User();
        $user->setSite($nantes);
        $user->setEmail('test@test.test');
        $user->setPassword('password');
        $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()));
        $user->setFirstName('Jean');
        $user->setLastName('Bonbeurre');
        $user->setPhone('0000000000');
        $user->setUsername('test');
        $manager->persist($user);
        $manager->flush();
        $populator->addEntity(User::class, 10, [
            'password' => 'password',
            'site' => function() use ($nantes, $rennes) {
                return rand(1,2) % 2 ? $nantes : $rennes;
            }
            ] ,[ function(User $user) {
                $user->setPassword($this->hasher->hashPassword($user, 'password'));
            }
        ]);
        $populator->execute();
        $manager->flush();
        $users = $manager->getRepository(User::class)->findAll();
        $populator->addEntity(Hangout::class, 500, [
            'startTimestamp' => function() use ($generator){
              return $generator->dateTimeBetween('-5 years', '+1 year');
            },
            'creator' => function() use ($users){return $users[rand(0,9)];},
            'duration'=> new \DateInterval(sprintf('PT%sH%sM', rand(1,3), rand(1,60))),
            'site'=> function() use ($nantes, $rennes) {return rand(1,2)%2 ? $nantes : $rennes;},
            'state'=>function() { return StateConstraints::wordingState[rand(0, sizeof(StateConstraints::wordingState)-1)];},
            'name'=>function() use($generator) {return implode(" ",$generator->words(3));}
        ], [
            function ($hangout) {
                if($hangout instanceof Hangout){
                    $date = $hangout->getStartTimestamp();
                    if($date instanceof DateTime) {
                    $registerDate = clone $date;
                    $registerDate->sub(new \DateInterval('P1D'));
                    $hangout->setLastRegisterDate($registerDate);
                }
            }
            }
        ]);
        $populator->execute();
        $manager->flush();
    }
}
