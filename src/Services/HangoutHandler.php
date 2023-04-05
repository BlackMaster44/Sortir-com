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
            $hangout->setIsPublished(false);
        }
        if($action === 'published') {
            $hangout->setState(StateConstraints::REG_OPEN);
            $hangout->setIsPublished(true);
        }
        $this->manager->persist($hangout);
        $this->manager->flush($hangout);
    }
}