<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Dishes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('category',EntityType::class, [
                'class' =>Category::class
            ] )
            ->add('price')
            ->add('image', FileType::class)  //['mapped =>false'], ['required' =>false]
            ->add('save', SubmitType::class)
            // unmapped means that this field is not associated to any entity property
            // make it optional, so you don't have to re-upload the PDF file every time you edit the Product details
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dishes::class,
        ]);
    }
}
