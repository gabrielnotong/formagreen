<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\TrainingCenter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingCenterProfileType extends TrainingCenterRegisterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('hash')
            ->remove('passwordConfirm')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingCenter::class,
        ]);
    }
}
