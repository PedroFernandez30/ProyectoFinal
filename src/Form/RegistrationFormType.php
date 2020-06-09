<?php

namespace App\Form;

use App\Entity\Canal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'translation_domain' => 'form',
                
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Apellidos',
                'translation_domain' => 'form',
            ])
            ->add('email', EmailType::class,[
                'label' => 'Correo electrónico',
                'translation_domain' => 'form',
            ])
            ->add('nombreCanal', TextType::class, [
                'label' => 'Nombre del canal',
                'translation_domain' => 'form',
            ])
            /*->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])*/
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Contraseña',
                'translation_domain' => 'form',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor introduzca una contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])->add('sexo', ChoiceType::class, [
                'choices'  => [
                    'Hombre' => 'H',
                    'Mujer' => 'M',
                    'Otro' => 'O',
                ],
                'label' => 'Sexo',
                'translation_domain' => 'form',
                'expanded' => true

            ])
            ->add('fnac', DateType::class, [
                'label' => 'Fecha de nacimiento',
                'translation_domain' => 'form',
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y'),date('Y')-120),

            ])->add('fotoPerfil', FileType::class,[
                'required' => false,
                'mapped' => false,
                'label' => 'Foto de perfil (la puedes elegir ahora o en cualquier momento)',
                'translation_domain' => 'form',
                'empty_data' => 'imgPerfil/profile.jpg',
                'constraints' => [
                    new File([
                         'maxSize' => '1024k',
                         'mimeTypes' => [
                             'image/*'
                         ],
                     ])
                 ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Canal::class,
        ]);
    }
}
