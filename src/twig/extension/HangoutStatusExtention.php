<?php

namespace App\twig\extension;

use App\TypeConstraints\StateConstraints;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HangoutStatusExtention extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('status', [$this, 'interpretStatus'], ['is_safe'=>['html']])
        ];
    }

    public function interpretStatus(string $dbStatus)
    {
        if(!in_array($dbStatus, StateConstraints::wordingState)){
            return 'invalid status. Contact an administrator';
        }
        return match ($dbStatus) {
            StateConstraints::REG_OPEN => '<div class="status"><i class="fa-solid fa-check"></i><p>registration open</p></div>',
            StateConstraints::CREATED => '<div class="status"><i class="fa-regular fa-clock"></i><p>created</p></div>',
            StateConstraints::DONE => '<div class="status"><i class="fa-solid fa-flag-checkered"></i><p>finished</p></div>',
            StateConstraints::CANCELED => '<div class ="status"><i class="fa-solid fa-xmark"></i><p>canceled</p></div>',
            StateConstraints::IN_PROGRESS => '<div class="status"><i class="fa-solid fa-spinner"></i><p>in progress</p></div>',
            StateConstraints::REG_CLOSED => '<div class="status"><i class="fa-solid fa-ban"></i><p>registration closed</p></div>',
            default => 'invalid status. Contact an administrator',
        };
    }

}