<?php

namespace AppBundle\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ClienteRestController extends ApiRestController
{


//    public function postCommentVoteAction($slug, $id)
//    {} // "post_user_comment_vote" [POST] /users/{slug}/comments/{id}/vote
//
//    public function getCommentsAction($slug)
//    {} // "get_user_comments"   [GET] /users/{slug}/comments
//
//    public function getCommentAction($slug, $id)
//    {} // "get_user_comment"    [GET] /users/{slug}/comments/{id}
//
//    public function deleteCommentAction($slug, $id)
//    {} // "delete_user_comment" [DELETE] /users/{slug}/comments/{id}
//
//    public function newCommentsAction($slug)
//    {} // "new_user_comments"   [GET] /users/{slug}/comments/new
//
//    public function editCommentAction($slug, $id)
//    {} // "edit_user_comment"   [GET] /users/{slug}/comments/{id}/edit
//
//    public function removeCommentAction($slug, $id)
//    {} // "remove_user_comment" [GET] /users/{slug}/comments/{id}/remove

    public function getClienteAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $cliente = $em->getRepository("AppBundle:Cliente")->find($request->get('id'));

        $baseURL = $this->getPathImage('app.path.personas_image', $request);

        $cliente->setImage( $baseURL . $cliente->getImage());

        $vista = $this->view($cliente,
            200);

        return $this->handleView($vista);
    }
}
