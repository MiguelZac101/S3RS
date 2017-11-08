<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session; //session

use BackendBundle\Entity\User; //entidad
use BackendBundle\Entity\PrivateMessage;

class PrivateMessageController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function indexAction(Request $request) {
        return $this->render('AppBundle:PrivateMessage:index.html.twig', array(
            "titulo" => "MENSAJE DE PRUEBA"
        ));
    }

}
