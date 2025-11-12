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
                'label' => 'Marca',
                'choices' => Brand::cases(),
                'choice_label' => fn(Brand $brand)=>'brand.'. ($brand->value),
                'choice_value' => fn(?Brand $brand) => $brand?->value, 
          
                'translation_domain' => 'enum',
                ])
                ->add('model', TextType::class, [
                'label' => 'model',
                'translation_domain' => 'message',
                'attr' => ['placeholder' => 'Modelo'],
                ])
                ->add('description', TextType::class,[
                    'label' => 'description',
                    'attr' => ['placeholder' => 'Escribe una descripción breve del coche'],
                    'translation_domain' => 'message',
                ])
                ->add('photo', TextType::class,[
                    'label' => 'photo',
                    'translation_domain' => 'message',
                    'attr' => ['placeholder' => 'Subir imagen'],
                    

                ])
                ->add('price', NumberType::class,[
                    'label' => 'price',
                    'translation_domain' => 'message',
                    'attr' => ["min" => 0 ,'placeholder' => 'Precio (€)'],
                    'scale' => 2,


                ])
                ->add('year', NumberType::class,[
                    'label' => 'year',
                    'translation_domain' => 'message',
                    'attr' => [
                        'min' => 1900, 
                        'max' => (new \DateTime())->format('Y'),
                        
                    ],
                    'html5' => true,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }

 }