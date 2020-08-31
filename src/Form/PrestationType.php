<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Discount;
use App\Entity\GreenSpace;
use App\Entity\Prestation;
use App\Entity\User;
use App\Form\DataTransformer\StringToDateTimeTransformer;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestationType extends ApplicationType
{
    private StringToDateTimeTransformer $transformer;

    public function __construct(StringToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'userMember',
                EntityType::class, [
                    'query_builder' => function (UserRepository $r) {
                        return $r->findAllMembers();
                    },
                    'class'         => User::class,
                    'label'         => 'Who is taking in charge the prestation?'
                ]
            )
            ->add(
                'greenSpace',
                EntityType::class, [
                    'class' => GreenSpace::class,
                    'label' => 'Which green space?'
                ]
            )
            ->add(
                'type',
                ChoiceType::class, [
                    'label'   => 'Which type of prestation?',
                    'choices' => [
                        'Maintenance'  => 'Maintenance',
                        'Installation' => 'Installation'
                    ]
                ]
            )
            ->add(
                'discount',
                EntityType::class, [
                    'required'    => false,
                    'class'       => Discount::class,
                    'label'       => 'Any discount to apply?',
                    'placeholder' => '--- Choose a discount ---'
                ]
            )
            ->add(
                'startsAt',
                TextType::class,
                $this->getConfiguration('From', 'Format: yyyy/mm/dd'),
            )
            ->add(
                'endsAt',
                TextType::class,
                $this->getConfiguration('To', 'Format: yyyy/mm/dd'),
            )
        ;

        $builder->get('startsAt')->addModelTransformer($this->transformer);
        $builder->get('endsAt')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prestation::class
        ]);
    }
}
