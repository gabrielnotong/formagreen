<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\TrainingCenter;
use App\Entity\UserLambda;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingCenterRegisterType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                $this->getConfiguration('Company Name', 'The company name...'),
            )
            ->add(
                'email',
                EmailType::class,
                $this->getConfiguration('Email', 'Email...'),
            )
            ->add(
                'address',
                TextType::class,
                $this->getConfiguration('Address', 'Company address...'),
            )
            ->add(
                'country',
                TextType::class,
                $this->getConfiguration('Country', 'Country where the company is based...'),
            )
            ->add(
                'city',
                TextType::class,
                $this->getConfiguration('City', 'City where the company is located...'),
            )
            ->add(
                'phoneNumber',
                TextType::class,
                $this->getConfiguration('Phone number', 'Enter your phone number here...'),
            )
            ->add(
                'hash',
                PasswordType::class,
                $this->getConfiguration('Password', 'Your Password...'),
            )
            ->add(
                'passwordConfirm',
                PasswordType::class,
                $this->getConfiguration('Password Confirmation', 'Once again your Password...'),
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingCenter::class,
        ]);
    }
}
