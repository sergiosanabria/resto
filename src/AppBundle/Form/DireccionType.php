<?php

namespace AppBundle\Form;

use AppBundle\Services\EmpresaManager;
use Doctrine\ORM\EntityRepository;
use Matudelatower\UbicacionBundle\Form\EventListener\AddDepartamentoFieldSubscriber;
use Matudelatower\UbicacionBundle\Form\EventListener\AddLocalidadFieldSubscriber;
use Matudelatower\UbicacionBundle\Form\EventListener\AddPaisFieldSubscriber;
use Matudelatower\UbicacionBundle\Form\EventListener\AddProvinciaFieldSubscriber;   
use Matudelatower\UbicacionBundle\Form\Type\MatudelatowerLocalidadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UsuariosBundle\Services\UsuarioManager;

class DireccionType extends AbstractType
{
    private $empresaManager;

    public function __construct(EmpresaManager $empresaManager)
    {
        $this->empresaManager = $empresaManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $factory = $builder->getFormFactory();

        $empresa = $this->empresaManager->getEmpresaLogueada();

        $localidad = $empresa->getDireccion()->getLocalidad();

        $builder->addEventSubscriber( new AddPaisFieldSubscriber( $factory ) );
        $builder->addEventSubscriber( new AddProvinciaFieldSubscriber( $factory ) );
        $builder->addEventSubscriber( new AddDepartamentoFieldSubscriber( $factory ) );
        $builder->addEventSubscriber( new AddLocalidadFieldSubscriber( $factory, $localidad ) );

        $builder

            ->add('nombre')
            ->add('calle')
            ->add('altura')
            ->add('dpto')
            ->add('piso')
            ->add('edificio')
            ;


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Direccion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_direccion';
    }


}
