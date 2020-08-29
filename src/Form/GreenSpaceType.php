<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\GreenSpace;
use App\Entity\TrainingCenter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GreenSpaceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                $this->getConfiguration('Name', 'Green space name...')
            )
            ->add(
                'trainingCenter',
                EntityType::class, [
                    'class'        => TrainingCenter::class,
                    'choice_label' => 'companyName',
                    'label'        => 'Where is it located?'
                ]
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
                $this->getConfiguration('City', 'City where the green space is located...'),
            )
            ->add(
                'country',
                TextType::class,
                $this->getConfiguration('Country', 'Country where the green space is based...'),
            )
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GreenSpace::class
        ]);
    }
}
