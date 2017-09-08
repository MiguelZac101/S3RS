<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use BackendBundle\Entity\Publication; //(1)
use AppBundle\Form\PublicationType; //(2)

class PublicationController extends Controller{
    
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $publication = new Publication(); //(1)        
        $form = $this->createForm(PublicationType::class,$publication);//(2)
        
        return $this->render('AppBundle:Publication:home.html.twig',array(
            'form' => $form->createView()
        ));
    }
    
}
