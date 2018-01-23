<?php

namespace AppBundle\Controller\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ApiRestController extends FOSRestController
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

    public function getPathImage($parameter, Request $request)
    {


        $baseURL =  $request->getBasePath()  . $this->getParameter($parameter);


        return $baseURL;
    }
}
