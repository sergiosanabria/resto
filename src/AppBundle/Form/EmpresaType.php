<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EmpresaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('slogan')
            ->add('descripcion')
            ->add('imageFile',
                VichImageType::class,
                array(
                    'label' => 'Logo de la empresa',
                    'required' => false,
                    'allow_delete' => true, // not mandatory, default is true
                    'download_link' => true, // not mandatory, default is true
                ))
            ->add('color')
            ->add('controlaStock')
            ->add('telefonoPrincipal')
            ->add('telefonoSecundario')
            ->add('mail')
            ->add('sitioWeb')
            ->add('facebook')
            ->add('instagram')
            ->add('twitter')
            ->add('activo')
            ->add('direccion', DireccionType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Empresa'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_empresa';
    }


}
