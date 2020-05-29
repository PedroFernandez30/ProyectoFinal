<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\VideoRepository;


class PruebaController extends AbstractController
{
    /**
     * @Route("/prueba/{previo?}", name="prueba")
     */
    public function index(VideoRepository $videoRepository, Request $request, $previo = '')
    {
        //$previo = $_REQUEST['previo'];
        $previo = $request->request->get('previo');
        //{_locale}
        //, requirements={"_locale"="en|fr|es"}
        // lógica para determinar el $locale
        $locale = $request->getLocale();
        /*return $this->redirectToRoute('app_login', [
            '_locale' => 'es',
        ]);*/
        if($locale == 'es') {
            $request->setLocale('en');
            return $this->redirectToRoute('app_login', [
                '_locale' => 'en'
            ]);
        } else {
            $request->setLocale('es');
            return $this->redirectToRoute('app_login', [
                '_locale' => 'es'
            ]);
        }
        
        
    }

}
