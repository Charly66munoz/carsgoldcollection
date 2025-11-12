<?php

namespace App\Form;

use App\Entity\Car;
use App\Enum\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand',ChoiceType::class, [
                'choices' => Brand::cases(),
                'choice_label' => fn(Brand $brand)=>'brand.'. strtolower($brand->value),
                'choice_value' => fn(?Brand $brand) => $brand?->value, 
                'translation_domain' => 'enum',
                ])
            ->add('model')
            ->add('description')
            ->add('photo')
            ->add('price' , NumberType::class)
            ->add('year')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
