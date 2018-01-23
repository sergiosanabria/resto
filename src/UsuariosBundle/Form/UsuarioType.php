<?php

namespace UsuariosBundle\Form;

use AppBundle\Form\UsuarioRolType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UtilBundle\Form\Type\BootstrapCollectionType;

class UsuarioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $showPass = isset($options['attr']['show_password']) ? $options['attr']['show_password'] : false;

        if ($showPass) {
            $builder->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Repita su contraseña'),
            ));
        }

        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('persona')
            ->add("rolesUsuario", BootstrapCollectionType::class,
                array(
                    'entry_type' => UsuarioRolType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                )
            )
           ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UsuariosBundle\Entity\Usuario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'usuariosbundle_usuario';
    }


}
