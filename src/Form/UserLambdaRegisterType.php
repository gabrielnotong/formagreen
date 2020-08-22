<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use App\Entity\UserLambda;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserLambdaRegisterType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                $this->getConfiguration('First name', 'Your First name...'),
            )
            ->add(
                'lastName',
                TextType::class,
                $this->getConfiguration('Last name', 'Your Last name...'),
            )
            ->add(
                'phoneNumber',
                TextType::class,
                $this->getConfiguration('Phone number', 'Enter your phone number here...'),
            )
            ->add(
                'email',
                EmailType::class,
                $this->getConfiguration('Email', 'Your email address...'),
            )
            ->add(
                'numberOfMonths',
                ChoiceType::class,
                [
                    'label' => 'How long will you stay with us? (in months)',
                    'choices' => User::NUMBER_OF_MONTHS,
                ],
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
            'data_class' => UserLambda::class,
        ]);
    }
}
