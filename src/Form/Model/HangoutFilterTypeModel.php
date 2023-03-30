<?php

namespace App\Form\Model;
use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;

class HangoutFilterTypeModel
{
    #[Assert\Type(Site::class)]
    #[Assert\NotBlank]
    public $site;
    #[Assert\Type('string')]
    public $searchQuery;
    #[Assert\Type(\DateTime::class)]
    public $from;
    #[Assert\type(\DateTime::class)]
    #[Assert\GreaterThanOrEqual(
        propertyPath: 'from',
        message: '{{ value }} is inferior to {{ compared_value }}'
    )]
    public $to = null;
    #[Assert\Type('boolean')]
    public $isOrganizer = true;
    #[Assert\Type('boolean')]
    public $isSubscribed = true;
    #[Assert\Type('boolean')]
    public $isNotSubscribed = true;
    #[Assert\Type('boolean')]
    public $isExpired = false;
    #[Assert\Type('integer')]
    public $userId = 0;

    public function __construct(EntityManagerInterface $em)
    {
        $this->site = $em->getRepository(Site::class)->findOneBy(['name'=>'Nantes']);
        $this->from = new \DateTime();
    }
}