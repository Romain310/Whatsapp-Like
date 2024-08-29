<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CommissionTemporaireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class)
            ->add('debut', DateType::class)
            ->add('cloture', DateType::class)
            ->add('submitbutton', SubmitType::class, array(
                'label' => "Créer",
                'attr' => array('class' => 'btn btn-primary')
            ));
        ;
    }
}
