<?php

namespace AppBundle\Form;

use AppBundle\Entity\Precio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrecioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('precio')
            ->add('tipoPrecio', ChoiceType::class, array(
                'choices' => array(
                    'Precio de venta' => Precio::$tipoPrecioVenta,
                    'Precio de costo' => Precio::$tipoPrecioCosto,
                ),
            ))
            ->add('producto')
            ->add('combo');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Precio'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_precio';
    }


}
