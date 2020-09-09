<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\CenterType;
use App\Entity\TrainingCenter;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                'companyName',
                TextType::class,
                $this->getConfiguration('Company Name', 'The company name...'),
            )
            ->add(
                'email',
                EmailType::class,
                $this->getConfiguration('Email', 'Email...'),
            )
            ->add(
                'streetNumber',
                IntegerType::class,
                $this->getConfiguration('Street Number', ''),
            )
            ->add(
                'streetName',
                TextType::class,
                $this->getConfiguration('Street Name', 'Enter street name here...'),
            )
            ->add(
                'zipCode',
                TextType::class,
                $this->getConfiguration('Zip code', 'Enter zip code...'),
            )
            ->add(
                'city',
                TextType::class,
                $this->getConfiguration('City', 'City where the company is located...'),
            )
            ->add(
                'country',
                TextType::class,
                $this->getConfiguration('Country', 'Country where the company is based...'),
            )
            ->add(
                'phoneNumber',
                TextType::class,
                $this->getConfiguration('Phone number', 'Enter your phone number here...'),
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
                'centerType',
                EntityType::class, [
                    'class' => CenterType::class,
                    'choice_label' => 'name'
            ])
            ->add(
                'plainTextPassword',
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
            'validation_groups' => ['training']
        ]);
    }
}
