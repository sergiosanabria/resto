<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class PedidoCabeceraType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('observacion')
            ->add('delivery')
            ->add('estado')
            ->add('motivoCancelacionCliente')
            ->add('motivoCancelacionEmpresa')->add('total')->add('activo')->add('fechaCreacion')->add('fechaActualizacion')->add('empresa')
//            ->add('cliente', EntityType::class, array(
//                'class' => 'AppBundle\Entity\Cliente',
//                'attr' => array(
//                    'v-model' => 'cliente'
//                )
//            ))
            ->add('cliente', \UtilBundle\Form\Type\Select2EntityType::class, array(
                'remote_route' => 'cliente_ajax_por_empresa',
                'class' => 'AppBundle\Entity\Cliente',
                'required' => false,
                'placeholder' => 'Buscar cliente',
                'attr' => array(
                    'data-v-model' => 'cliente'
                )))
            ->add('repartidor')->add('creadoPor')->add('actualizadoPor');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PedidoCabecera'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_pedidocabecera';
    }


}
