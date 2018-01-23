<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectorMesaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array(
                'attr' => array(
                    'v-model' => "nombre"
                )
            ))
            ->add('descripcion', TextType::class, array(
                'attr' => array(
                    'v-model' => "descripcion"
                )
            ))
            ->add('cantidadColumnas', IntegerType::class, array(
                'attr' => array(
                    'v-model' => "cantidadColumnas"
                )
            ))
            ->add('cantidadMesas', IntegerType::class, array(
                'attr' => array(
                    'v-model' => "cantidadMesas"
                )
            ))
            ->add('activo');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SectorMesa'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_sectormesa';
    }


}
