<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Discount;
use App\Entity\Partner;
use App\Form\DataTransformer\StringToDateTimeTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountType extends ApplicationType
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
                'percentage',
                NumberType::class,
                $this->getConfiguration('Which percentage do you want to offer', 'Enter discount percentage...')
            )
            ->add(
                'partner',
                EntityType::class, [
                    'class' => Partner::class,
                    'choice_label' => 'name',
                    'label' => 'Who is the partner?'
                ]
            )
            ->add(
                'description',
                TextType::class,
                $this->getConfiguration('Description', 'Describe the discount'),
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
            'data_class' => Discount::class
        ]);
    }
}
