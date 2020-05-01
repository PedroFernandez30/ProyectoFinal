<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('duracion', HiddenType::class, [
                'empty_data' => 'Duración'
            ])
            ->add('fechaPublicacion',HiddenType::class, [
                'data' => date("Y/m/d")
            ])
            ->add('idCanal', HiddenType::class)
            ->add('idCategoria', null, [
                'label' => 'Categoría'
            ])
            ->add('miniatura', FileType::class,[
                'required' => true,
                'mapped' => false,
                'label' => 'Miniatura',
                'constraints' => [
                    new File([
                         'maxSize' => '1024K',
                         'mimeTypes' => [
                             'image/*'
                         ],
                     ])
                ]
            ])
            ->add('video', FileType::class,[
                'required' => true,
                'mapped' => false,
                'label' => 'Vídeo',
                'constraints' => [
                    new File([
                         'maxSize' => '512M',
                         'mimeTypes' => [
                             'video/*'
                         ],
                     ])
                ]
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
