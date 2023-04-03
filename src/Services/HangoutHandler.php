<?php

namespace App\Services;

use App\Entity\Hangout;
use App\TypeConstraints\StateConstraints;
use Doctrine\ORM\EntityManagerInterface;

class HangoutHandler
{

    public function __construct(
        private EntityManagerInterface $manager
    ){}

    public function save(Hangout $hangout, string $action): void
    {
        if ($action === 'saved') {
            $hangout->setState(StateConstraints::CREATED);
        }
        if($action === 'published') {
            $hangout->setState(StateConstraints::REG_OPEN);
        }
        $this->manager->persist($hangout);
        $this->manager->flush($hangout);
    }
}