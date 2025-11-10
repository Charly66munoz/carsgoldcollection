<?php
 namespace App\Form\Entity;

use App\Entity\Car;
use App\Enum\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

 final class CarForm extends AbstractType
 {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('brand',ChoiceType::class, [
                'choices' => Brand::cases(),
                'choice_label' => fn(Brand $brand)=> $brand->value,
                'choce_value' => fn(Brand $brand)=> $brand->value, //podemos ponerl ? si puediera ser null
                ])
                ->add('model', TextType::class)
                ->add('desciprtion', TextType::class)
                ->add('photo', TextType::class)
                ->add('price', NumberType::class)
                ->add('year', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }

 }