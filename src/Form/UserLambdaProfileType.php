<?php

namespace App\Form;

use App\Entity\UserLambda;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserLambdaProfileType extends UserLambdaRegisterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('numberOfMonths')
            ->remove('hash')
            ->remove('passwordConfirm')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserLambda::class,
        ]);
    }
}
