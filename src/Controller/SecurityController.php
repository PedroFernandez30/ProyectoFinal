<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
   
    /**
     * @Route({"es": "/inicia_sesion/{previo?}","en": "/login/{previo?}"} , name="app_login") 
     */
    public function login(Request $request, $previo = '' , AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        //requirements="_locale={en|fr|de}"
        $request->request->set('previo', '');
        //$previo = $request->request->get('previo');
        //$previo = $_REQUEST['previo'];
        if($previo != '') {
            //throw Exception;
            $previous = str_replace('-','/', $previo);
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'previo' => $previo/*  'noError' => true,'previo' => $previous*/]);
        } else {
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }

        
    }

    /**
     * @Route("/logout/{id?}", name="app_logout")
     */
    public function logout(Canal $canal = null)
    {
        
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
