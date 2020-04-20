<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class PruebaController extends AbstractController
{
    /**
     * @Route("/prueba/", name="prueba")
     */
    public function index(Request $request)
    {
        //{_locale}
        //, requirements={"_locale"="en|fr|es"}
        // lógica para determinar el $locale
        $locale = $request->getLocale();
        /*return $this->redirectToRoute('app_login', [
            '_locale' => 'es',
        ]);*/
        return $this->render('prueba/index.html.twig');
    }
}
