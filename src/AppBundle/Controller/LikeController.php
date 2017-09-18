<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Publication; //(1)
use BackendBundle\Entity\User;
use BackendBundle\Entity\Like;
use Symfony\Component\HttpFoundation\Session\Session; //session

class LikeController extends Controller {

    public function likeAction($id = null) {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $publication_repo = $em->getRepository("BackendBundle:Publication");
        $publication = $publication_repo->find($id);

        $like = new Like();
        $like->setUser($user);
        $like->setPublication($publication);

        $em->persist($like);
        $flush = $em->flush();

        if ($flush == null) {
            $status = 'Tegusta esta blicacion!!';
        } else {
            $status = 'No se ha podido guardar el me gusta!!';
        }

        return new Response($status);
    }

    public function unlikeAction($id = null) {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
   
        $like_repo = $em->getRepository("BackendBundle:Like");
        $like = $like_repo->findOneBy(array(
            "user" => $user,
            "publication" => $id
        ));

        $em->remove($like);
        $flush = $em->flush();

        if($flush == null){
            $status = 'like se ha borrado correctamente';
        }else{
            $status = 'like no se ha borrado';
        }

        return new Response($status);
    }

}
