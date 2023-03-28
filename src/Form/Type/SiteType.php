<?php
namespace App\Form\Type;

use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $sites = $this->em->getRepository(Site::class)->findAll();
        $resolver->setDefaults([
            'choices' => $sites,
            'class' => Site::class,
            'choice_label'=>'name'
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }

}
