<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Hangout;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\State;
use App\Entity\User;
use App\TypeConstraints\StateConstraints;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
        for($i = 0; $i < sizeof(StateConstraints::wordingState); $i++){
            $state = new State();
            $state->setWording(StateConstraints::wordingState[$i]);
            $manager->persist($state);
        }
        $manager->flush();
        $populator->addEntity(City::class, 10, [
            'name'=>function() use ($generator){ return $generator->city();}
        ]);
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
        $places = $manager->getRepository(Place::class)->findAll();
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
        $states = $manager->getRepository(State::class)->findAll();
        $populator->addEntity(Hangout::class, 50, [
            'creator' => $users[rand(0,9)],
            'duration'=> new \DateInterval(sprintf('PT%sH%sM', rand(1,3), rand(1,60))),
            'participants'=> new ArrayCollection([$users[rand(0,9)],$users[rand(0,9)]]),
            'site'=> rand(1,2)%2 ? $nantes : $rennes,
            'state'=>function() use($states) { return $states[rand(0, sizeof(StateConstraints::wordingState)-1)];},
            'name'=>function() use($generator) {return $generator->realText(50, 1);}
        ],
            [
            function (Hangout $hangout) use ($manager){
                $users = [];
                for($i = 1; $i < 3; $i++) {
                    $users[] = $manager->getRepository(User::class)->findOneBy(['id'=>rand(1, 10)]);
                }
                $hangout->setParticipants(new ArrayCollection($users));
            }
        ]
        );
        $populator->execute();
        $manager->flush();
    }
}
