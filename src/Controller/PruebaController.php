<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class PruebaController extends AbstractController
{
    /**
     * @Route("/prueba", name="prueba")
     */
    public function index(Request $request)
    {
        // lógica para determinar el $locale
        $locale = $request->getLocale();
        $request->getSession()->set('_locale', 'es');
        $locale = $request->getLocale();
        return $this->render('prueba/index.html.twig', [
            'controller_name' => $locale,
        ]);
    }
}
