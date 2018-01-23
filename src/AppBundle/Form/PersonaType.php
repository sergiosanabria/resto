<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UsuariosBundle\Entity\Usuario;
use UsuariosBundle\Form\UsuarioType;
use UtilBundle\Form\Type\BootstrapCollectionType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PersonaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, array(
                'attr' => array(
                    'tabIndex' => '1',
                )
            ))
            ->add('apellido', null, array(
                'attr' => array(
                    'tabIndex' => '2',
                )
            ))
            ->add('fechaNacimiento',
                DateType::class,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => array(
                        'class' => 'datepicker',
                        'tabIndex' => '3',
                    ),
                ))
            ->add('sexo', ChoiceType::class, array(
                "required" => true,
                'choices' => array(
                    'Masculino' => "M",
                    'Femenino' => "F",
                ),
                'attr' => array(
                    'tabIndex' => '4',
                )
            ))
            ->add('perfil', null, array(
                'attr' => array(
                    'tabIndex' => '5',
                )
            ))
            ->add('telefonoPrincipal', null, array(
                'attr' => array(
                    'tabIndex' => '6',
                )
            ))
            ->add('telefonoSecundario', null, array(
                'attr' => array(
                    'tabIndex' => '7',
                )
            ))
            ->add('mail', EmailType::class, array(
                'attr' => array(
                    'tabIndex' => '5',
                )
            ))
//            ->add('background')
            ->add('imageFile',
                VichImageType::class,
                array(
                    'label' => 'Archivo',
                    'required' => false,
                    'allow_delete' => true, // not mandatory, default is true
                    'download_link' => true, // not mandatory, default is true
                ))
            ->add('direcciones', BootstrapCollectionType::class,
                array(
                    'entry_type' => DireccionType::class,
                    'label' => "Direccion principal",
                    'allow_add' => true,
                    "max_items_add" => 1,
                    'allow_delete' => true,
                    'by_reference' => false,
                )
            )
            ->add('activo');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Persona',
            'router' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_persona';
    }


}
