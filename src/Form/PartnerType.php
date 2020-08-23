<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Partner;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartnerType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                $this->getConfiguration('Name', 'Partner name...')
            )
            ->add(
                'streetNumber',
                TextType::class,
                $this->getConfiguration('Street number', 'Enter street number...')
            )
            ->add(
                'streetName',
                TextType::class,
                $this->getConfiguration('Street name', 'Enter street name...')
            )
            ->add(
                'country',
                TextType::class,
                $this->getConfiguration('Country', 'Enter country name...')
            )
            ->add(
                'city',
                TextType::class,
                $this->getConfiguration('City', 'Enter city name...')
            )
            ->add(
                'zipCode',
                TextType::class,
                $this->getConfiguration('Zip code', 'Enter zip code...')
            )
            ->add(
                'phoneNumber',
                TextType::class,
                $this->getConfiguration('Phone number', 'Enter phone number...')
            )
            ->add(
                'email',
                EmailType::class,
                $this->getConfiguration('Email', 'Enter email contact...')
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
            'validation_groups' => 'partner'
        ]);
    }
}
