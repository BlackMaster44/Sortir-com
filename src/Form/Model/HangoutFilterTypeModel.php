<?php

namespace App\Form\Model;
use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
class HangoutFilterTypeModel
{

    /**
     * @var Site|object|null
     */
    #[Assert\Type(Site::class)]
    #[Assert\NotBlank]
    public $site;
    /**
     * @var
     */
    #[Assert\Type('string')]
    public $searchQuery;
    /**
     * @var \DateTime
     */
    #[Assert\Type(\DateTime::class)]
    public $from;
    /**
     * @var null
     */
    #[Assert\type(\DateTime::class)]
    #[Assert\GreaterThanOrEqual(
        propertyPath: 'from',
        message: '{{ value }} is inferior to {{ compared_value }}'
    )]
    public $to = null;
    /**
     * @var bool
     */
    #[Assert\Type('boolean')]
    public $isOrganizer = true;
    /**
     * @var bool
     */
    #[Assert\Type('boolean')]
    public $isSubscribed = true;
    /**
     * @var bool
     */
    #[Assert\Type('boolean')]
    public $isNotSubscribed = true;
    /**
     * @var bool
     */
    #[Assert\Type('boolean')]
    public $isExpired = false;
    /**
     * @var int
     */
    #[Assert\Type('integer')]
    public $userId = 0;

    public function __construct(EntityManagerInterface $em)
    {
        $this->site = $em->getRepository(Site::class)->findOneBy(['name'=>'Nantes']);
        $this->from = new \DateTime();
    }
}