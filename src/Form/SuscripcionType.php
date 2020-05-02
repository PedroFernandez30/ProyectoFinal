<?php

namespace App\Form;

use App\Entity\Suscripcion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SuscripcionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('canalAlQueSuscribe', null, [
                'label' => 'Canal al que te suscribes'
            ])
            ->add('canalQueSuscribe', HiddenType::class, [
                'empty_data' => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Suscripcion::class,
        ]);
    }
}
