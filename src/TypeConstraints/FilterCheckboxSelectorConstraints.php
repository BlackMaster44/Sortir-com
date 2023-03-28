<?php

namespace App\TypeConstraints;
class FilterCheckboxSelectorConstraints{
    const CHOICES = [
        'CREATOR'=>'Sorties dont je suis l\'organisateur',
        'GOING'=>'Sorties auxquelles je suis inscrit',
        'NOTGOING'=>'Sorties auxquelles je ne suis pas inscrit',
        'ENDED'=>'Sorties passées'
    ];
}