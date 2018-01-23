<?php

namespace AppBundle\Services;

//use AppBundle\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UsuariosBundle\Services\UsuarioManager;

class EventSubscriber implements EventSubscriberInterface
{
    private $usuarioManager;
    private $empresaManager;

    public function __construct(UsuarioManager $usuarioManager, EmpresaManager $empresaManager)
    {
        $this->usuarioManager = $usuarioManager;
        $this->empresaManager = $empresaManager;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
//
//        $request = $event->getRequest();
//
//        // Matched route
//        $_route  = $request->attributes->get('_route');
//
//        // Matched controller
//        $_controller = $request->attributes->get('_controller');
//
//        // All route parameters including the `_controller`
//        $params      = $request->attributes->get('_route_params');


        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */

        /* @var $controller \Symfony\Bundle\FrameworkBundle\Controller\Controller */


        $controller = $event->getController()[0];

        $request = $event->getRequest();

        $_route = $request->attributes->get('_route');

        $allowRoutes = ['fos_user_security_login', 'rol_choice', 'empresa_choice', ''];

        if (is_null($_route) || in_array($_route, $allowRoutes)) {
            return;
        }

        if (substr($_route, 0, 1) === "_") {
            return;
        }

        if ($this->usuarioManager->getUser()->getRoot()) {

            return;

        }

        if (!is_array($event->getController())) {

            return;
        }


        if ($route = $this->usuarioManager->tienePermiso($_route)) {
            return;
        } else {
            throw new UnauthorizedHttpException('This action needs a valid token!');
        }

        //$empresaId = $controller->get("AppBundle\Services\EmpresaManager") ;

//        if ($controller[0] instanceof TokenAuthenticatedController) {
//            $token = $event->getRequest()->query->get('token');
//            if (!in_array($token, $this->tokens)) {
//                throw new AccessDeniedHttpException('This action needs a valid token!');
//            }
//        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}