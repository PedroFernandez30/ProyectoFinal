<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título'
            ])
            ->add('descripcion', TextType::class, [
                'label' => 'Descripción'
            ])
            ->add('mg', HiddenType::class, [
                'data' => 0,
            ])
            ->add('dislike', HiddenType::class, [
                'data' => 0,
            ])
            ->add('duracion', HiddenType::class)
            ->add('fechaPublicacion',HiddenType::class, [
                'data' => date("Y/m/d")
            ])
            ->add('idCanal', HiddenType::class)
            ->add('idCategoria', ChoiceType::class, [
                'label' => 'Categoría'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
