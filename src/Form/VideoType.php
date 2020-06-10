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
                'label' => 'Título',
                'translation_domain' => 'form'
            ])
            ->add('descripcion', TextType::class, [
                'label' => 'Descripción',
                'translation_domain' => 'form'
            ])
            /*->add('mg', HiddenType::class, [
                'data' => [],
            ])
            ->add('dislike', HiddenType::class, [
                'data' => [],
            ])*/
            ->add('duracion', HiddenType::class, [
                'empty_data' => 'Duración'
            ])
            /*->add('fechaPublicacion',HiddenType::class, [
                'data' => new \Datetime()
            ])*/
            ->add('idCanal', HiddenType::class)
            ->add('idCategoria', ChoiceType::class, [
                'choices'  => [
                    'Entretenimiento' => '1',
                    'Cultura' => '2',
                    'Deporte' => '3',
                    'Videojuegos' => '4'
                ],
                'mapped' => false,
                'label' => 'Categoría',
                'translation_domain' => 'form',
                'required' => true
            ])
            ->add('miniatura', FileType::class,[
                'required' => true,
                'mapped' => false,
                'label' => 'Miniatura',
                'translation_domain' => 'form',
                'constraints' => [
                    new File([
                         'mimeTypes' => [
                             'image/*'
                         ]
                         
                     ]),
                     new File([
                        'maxSize' => '3036K',
                     ])
                ]
            ])
            ->add('video', FileType::class,[
                'required' => true,
                'mapped' => false,
                'label' => 'Vídeo',
                'translation_domain' => 'form',
                'constraints' => [
                    new File([
                         'mimeTypes' => [
                             'video/*'
                         ],
                         'maxSize' => '512M',
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
