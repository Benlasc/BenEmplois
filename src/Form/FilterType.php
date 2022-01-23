<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('update', CheckboxType::class, ['required' => false, 'label' => 'Mettre à jour les résultats '])
            ->add('Paris', CheckboxType::class, ['required' => false, 'value' => '75 - PARIS'])
            ->add('Bordeaux', CheckboxType::class, ['required' => false, 'value' => '33 - BORDEAUX'])
            ->add('Rennes', CheckboxType::class, ['required' => false, 'value' => '35 - RENNES'])
            ->add('CDD', CheckboxType::class, ['required' => false, 'label' => 'CDD', 'value' => 'CDD'])
            ->add('CDI', CheckboxType::class, ['required' => false, 'label' => 'CDI', 'value' => 'CDI'])
            ->add('interim', CheckboxType::class, ['required' => false, 'label' => 'Intérim', 'value' => 'MIS'])
            ->add('saisonnier', CheckboxType::class, ['required' => false, 'value' => 'SAI'])
            ->add('professionnalisation', CheckboxType::class, ['required' => false, 'label' => 'Contrat de professionnalisation', 'value' => 'Cont. professionnalisation'])
            ->add('travail', CheckboxType::class, ['required' => false, 'label' => 'Contrat de travail', 'value' => 'Contrat travail'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
