<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ingrediente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UtilBundle\Form\Type\Select2EntityType;

class ProductoIngredienteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ingrediente', Select2EntityType::class, array(
            'remote_route' => 'ingrediente_ajax_por_empresa',
            'class' => 'AppBundle\Entity\Ingrediente',
            'required' => false,
            'placeholder' => 'Buscar ingredientes'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProductoIngrediente'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ingrediente';
    }


}
