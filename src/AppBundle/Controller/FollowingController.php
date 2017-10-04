<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session; //session
use BackendBundle\Entity\User; //entidad
use BackendBundle\Entity\Following;

class FollowingController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function followAction(Request $request) {
        $user = $this->getUser();
        $followed_id = $request->get('followed');

        $em = $this->getDoctrine()->getManager();

        $user_repo = $em->getRepository('BackendBundle:User');
        $followed = $user_repo->find($followed_id);

        $following = new Following();
        $following->setUser($user);
        $following->setFollowed($followed);

        $em->persist($following);
        $flush = $em->flush();

        if ($flush == null) {
            $status = "Ahora estas siquiendo a este usuario";
        } else {
            $status = "No se a podido seguir a este usuario";
        }

        return new Response($status);
    }

    public function unfollowAction(Request $request) {

        $user = $this->getUser();        
        $followed_id = $request->get('followed');        

        $em = $this->getDoctrine()->getManager();
        
        //
        $user_repo = $em->getRepository('BackendBundle:User');
        $user_followed = $user_repo->find($followed_id);
        //        

        $following_repo = $em->getRepository('BackendBundle:Following');

        //funciona para objetos como para el id

        $followed = $following_repo->findOneBy(array(
            'user' => $user,
            'followed' => $user_followed
        ));
      
        $em->remove($followed);
        $flush = $em->flush();

        if ($flush == null) {
            $status = "Has dejado de seguir a este usuario -> ".$followed_id;
        } else {
            $status = "No se a podido dejar de seguir a este usuario -> ".$followed_id;
        }

        return new Response($status);
    }
    
    public function followingAction(Request $request, $nickname = null){
        $em = $this->getDoctrine()->getManager();
        
        if($nickname != null){
            $user_repo = $em->getRepository("BackendBundle:User");
            $user = $user_repo->findOneBy(array("nick" => $nickname));
        }else {
            $user = $this->getUser();
        }
        
        if(empty($user) || !is_object($user)){
            return $this->redirect($this->generateUrl('home_publications'));
        }
        
        $user_id = $user->getId();
        $dql = "SELECT f FROM BackendBundle:Following f WHERE f.user = $user_id ORDER BY f.id DESC";
        $query = $em->createQuery($dql);
        
        $paginator = $this->get('knp_paginator');
        $following = $paginator->paginate($query, $request->query->getInt('page',1),5);
        
        return $this->render('AppBundle:Following:following.html.twig',array(
            'type' => 'following',
            'profile_user' => $user,
            'pagination' => $following
        ));
    }
}
