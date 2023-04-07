<?php

namespace App\Form\Model;
use App\Entity\Site;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Model for the homepage filter: carries data from a Symfony Form to a custom Doctrine Repository function.
 * Defaults to fetching a hardcoded Site when created TODO make it dynamic
 */
class HangoutFilterTypeModel
{
    /**
     * @var Site $site Site entity linked to the hangouts you want to display.
     */
    #[Assert\Type(Site::class)]
    #[Assert\NotBlank]
    public Site $site;
    /**
     * @var ?string $searchQuery Data from a text field.
     * Hangouts will be filtered by name according to %$searchQuery%.
     */
    #[Assert\Type('string')]
    public ?string $searchQuery = null;
    /**
     * @var ?DateTime $from Low limiter: no hangouts starting before this date will be shown.
     * Defaults to empty - no low clamp.
     * If empty, set to NOW in Repository.
     * Cannot be higher than $to.0
     */
    #[Assert\Type(DateTime::class)]
    public ?DateTime $from = null;
    /**
     * @var ?DateTime $to High limiter: no hangouts ending after this date will be shown.
     * defaults to empty - no high clamp.
     * if empty, set to DateTime MAX for current system.
     * Cannot be lower than $from.
     */
    #[Assert\type(DateTime::class)]
    #[Assert\GreaterThanOrEqual(
        propertyPath: 'from',
        message: '{{ value }} is inferior to {{ compared_value }}'
    )]


    public ?DateTime $to = null;
    /**
     * @var bool $isOrganizer Will add to the result array all Hangouts of which you are an organizer.
     *Defaults to true.
     */
    #[Assert\Type('boolean')]
    public bool $isOrganizer = true;
    /**
     * @var bool $isSubscribed Will add to the result array all Hangouts of which you are a participant.
     *Defaults to true.
     */
    #[Assert\Type('boolean')]
    public bool $isSubscribed = true;
    /**
     * @var bool $isNotSubscribed Will add to the result array all Hangouts of which you are NOT a participant.
     * Defaults to true.
     */
    #[Assert\Type('boolean')]
    public bool $isNotSubscribed = true;
    /**
     * @var bool $isExpired Will add to the result array all expired Hangouts matching $isOrganizer, $isSubscribed, or $isNotSubscribed.
     * If this flag is false, no expired hangouts will be shown.
     * Defaults to false.
     */
    #[Assert\Type('boolean')]
    public bool $isExpired = false;

    public User $user;

    public function __construct(EntityManagerInterface $em)
    {
        $this->site = $em->getRepository(Site::class)->findOneBy(['name'=>'Nantes']);
    }
}