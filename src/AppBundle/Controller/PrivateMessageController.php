<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session; //session

use BackendBundle\Entity\User; //entidad
use BackendBundle\Entity\PrivateMessage;

use AppBundle\Form\PrivateMessageType;//form

class PrivateMessageController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        
        $private_message = new PrivateMessage();
        $form = $this->createForm(PrivateMessageType::class, $private_message, array(
            'empty_data' => $user
        ));
        
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            if($form->isValid()){
                //upload image
                $file = $form['image']->getData();
                
                if(!empty($file) && $file!=null){
                    $ext = $file->guessExtension();
                    if($ext=='jpg' || $ext == 'jpeg' || $ext == 'png' || $ext =='gif'){
                        $file_name = $user->getId().time().".".$ext;
                        $file->move("uploads/messages/images",$file_name);
                        $private_message->setImage($file_name);
                    }else{
                        $private_message->setImage(null);
                    }
                }else{
                    $private_message->setImage(null);
                }
                
                //upload document
                $doc = $form['file']->getData();
                
                if(!empty($doc) && $doc!=null){
                    $ext = $doc->guessExtension();
                    if($ext=='jpg' || $ext == 'jpeg' || $ext == 'png' || $ext =='gif'){
                        $file_name = $user->getId().time().".".$ext;
                        $doc->move("uploads/messages/documents",$file_name);
                        $private_message->setFile($file_name);
                    }else{
                        $private_message->setFile(null);
                    }
                }else{
                    $private_message->setFile(null);
                }
                
                $private_message->setEmitter($user);
                $private_message->setCreatedAt(new \DateTime("now"));
                $private_message->setReaded(0);
                
                $em->persist($private_message);
                $flush = $em->flush();
                
                if($flush == null){
                    $status = "El mensaje privado se ha enviado correctamente!!";
                }else{
                    $status = "El mensaje privado no se ha enviado";
                }
                
            }else{
                $status = "El mensaje privado no se ha enviado";
            }
            
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("private_message_index");
        }
        
        return $this->render('AppBundle:PrivateMessage:index.html.twig', array(
            "form" => $form->createView()
        ));
    }

}
