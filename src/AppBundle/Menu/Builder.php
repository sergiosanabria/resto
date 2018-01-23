<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use UsuariosBundle\Services\UsuarioManager;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $usuarioManager;

    public function __construct()
    {
        //$this->usuarioManager = $this->container->get(UsuarioManager::class);
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {

        $this->usuarioManager = $this->container->get(UsuarioManager::class);
//		$menu = $factory->createItem('root');
//
//		$menu->addChild('Home', array('route' => 'app_homepage'));

        $menu = $factory->createItem(
            'root',
            array(
                'childrenAttributes' => array(
                    'class' => 'sidebar-menu tree',
                    'data-widget' => 'tree',
                ),
            )
        );


        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            $this->menuParametros($menu);

            $keyWeb = 'Personas';


            $menu->addChild(
                $keyWeb,
                array(
                    'childrenAttributes' => array(
                        'class' => 'treeview-menu',
                    ),
                )
            )
                ->setUri('#')
                ->setAttribute('class', 'treeview')
                ->setAttribute('icon', 'fa fa-user');


            $menu[$keyWeb]
                ->addChild(
                    'Clientes',
                    array(
                        'route' => 'cliente_index',
                    )
                );


            $this->menuProductos($menu);

            $this->menuPedidos($menu);

            $this->menuUsuario($menu);





        }

        return $menu;
    }

    private function menuProductos(&$menu)
    {

        $keyWeb = 'Productos';


        $menu->addChild(
            $keyWeb,
            array(
                'childrenAttributes' => array(
                    'class' => 'treeview-menu',
                ),
            )
        )
            ->setUri('#')
            ->setAttribute('class', 'treeview')
            ->setAttribute('icon', 'fa fa-home');


        $menu[$keyWeb]
            ->addChild(
                'Productos',
                array(
                    'route' => 'producto_index',
                )
            );
    }

    private function menuUsuario(&$menu)
    {

        $keyWeb = 'Usuario';


        $menu->addChild(
            $keyWeb,
            array(
                'childrenAttributes' => array(
                    'class' => 'treeview-menu',
                ),
            )
        )
            ->setUri('#')
            ->setAttribute('class', 'treeview')
            ->setAttribute('icon', 'fa fa-user');


        $menu[$keyWeb]
            ->addChild(
                'Usuarios',
                array(
                    'route' => 'usuarios',
                )
            );

        if ($this->usuarioManager->tienePermiso('rol', true)){
            $menu[$keyWeb]
                ->addChild(
                    'Roles',
                    array(
                        'route' => 'rol_index',
                    )
                );
        }

    }

    private function menuPedidos(&$menu)
    {

        if ($this->usuarioManager->tienePermiso('pedido', true)) {

            $keyWeb = 'Pedidos';


            $menu->addChild(
                $keyWeb,
                array(
                    'childrenAttributes' => array(
                        'class' => 'treeview-menu',
                    ),
                )
            )
                ->setUri('#')
                ->setAttribute('class', 'treeview')
                ->setAttribute('icon', 'fa fa-car');


            $menu[$keyWeb]
                ->addChild(
                    'Pedidos',
                    array(
                        'route' => 'pedidocabecera_index',
                    )
                );

        }


    }

    private function menuParametros(&$menu)
    {
        $keyWeb = 'Parámetros';


        $menu->addChild(
            $keyWeb,
            array(
                'childrenAttributes' => array(
                    'class' => 'treeview-menu',
                ),
            )
        )
            ->setUri('#')
            ->setAttribute('class', 'treeview')
            ->setAttribute('icon', 'fa fa-cogs');


        $menu[$keyWeb]
            ->addChild(
                'Categoría',
                array(
                    'route' => 'categoria_index',
                )
            );
        $menu[$keyWeb]
            ->addChild(
                'Ingrediente',
                array(
                    'route' => 'ingrediente_index',
                )
            );
        $menu[$keyWeb]
            ->addChild(
                'Sector',
                array(
                    'route' => 'sectormesa_index',
                )
            );
        $menu[$keyWeb]
            ->addChild(
                'Unidades de medida',
                array(
                    'route' => 'unidadmedida_index',
                )
            );


    }
}
