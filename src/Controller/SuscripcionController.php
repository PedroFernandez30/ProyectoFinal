<?php

namespace App\Controller;

use App\Entity\Suscripcion;
use App\Entity\Canal;
use App\Form\SuscripcionType;
use App\Repository\SuscripcionRepository;
use App\Repository\VideoRepository;
use App\Repository\CanalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/suscripcion")
 */
class SuscripcionController extends AbstractController
{
    /**
     * @Route("/", name="suscripcion_index", methods={"GET"})
     */
    public function index(SuscripcionRepository $suscripcionRepository, CanalRepository $canalRepository): Response
    {
        return $this->render('suscripcion/index.html.twig', [
            'suscripcions' => $suscripcionRepository->findAll(),
            'canals' => $canalRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="suscripcion_new", methods={"GET","POST"})
     */
    public function new (VideoRepository $videoRepository, CanalRepository $canalRepository, Request $request, UserInterface $canalActivo): Response
    {
        $suscripcion = new Suscripcion();
        if($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            $canalAlQueSuscribeId = json_decode($data['canalAlQueSuscribeId']);
            if($canalAlQueSuscribeId != null) {
                $canalAlQueSuscribeEntity = $canalRepository->findOneBy(['id' => $canalAlQueSuscribeId]);

                $suscripcion->setCanalQueSuscribe($canalActivo);
                $suscripcion->setCanalAlQueSuscribe($canalAlQueSuscribeEntity);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($suscripcion);
                $entityManager->flush();

                $videoId = json_decode($data ['idVideo']);
                

                $idSuscritosAlCanal = $this->getSuscritosACanal($canalAlQueSuscribeEntity);
                $this->addFlash('success', 'Te has suscrito a este canal');
                
                if($data['idVideo'] != null) {
                    $videoEntity = $videoRepository->findOneBy(['id' => $videoId]);
                    return new JsonResponse([
                        'contenido' => $this->render('suscripcion/toggleSuscripcion.html.twig', [
                            'idSuscritosAlCanal' => $idSuscritosAlCanal,
                            'video' => $videoEntity
                        ])->getContent(),
                        'numeroSuscriptores' => \count($idSuscritosAlCanal)
                    ]);
                }else {
                    return new JsonResponse([
                        'contenido' => $this->render('suscripcion/toggleSuscripcion.html.twig', [
                            'idSuscritosAlCanal' => $idSuscritosAlCanal,
                            'canal' => $canalAlQueSuscribeEntity
                        ])->getContent(),
                        'numeroSuscriptores' => \count($idSuscritosAlCanal)
                    ]);
                }
                

            }

        }
        
    }

    /**
     * @Route("/{id}", name="suscripcion_show", methods={"GET"})
     */
    public function show(Suscripcion $suscripcion): Response
    {
        return $this->render('suscripcion/show.html.twig', [
            'suscripcion' => $suscripcion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="suscripcion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Suscripcion $suscripcion): Response
    {
        $form = $this->createForm(SuscripcionType::class, $suscripcion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('suscripcion_index');
        }

        return $this->render('suscripcion/edit.html.twig', [
            'suscripcion' => $suscripcion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete", name="suscripcion_delete", methods={"DELETE"})
     */
    public function delete(VideoRepository $videoRepository, CanalRepository $canalRepository, SuscripcionRepository $suscripcionRepository, Request $request): Response
    {
        if($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            
            $canalSuscritoId = json_decode($data['canalSuscritoId']);
            $canalAlQueSuscribeId = json_decode($data['canalAlQueSuscribeId']);
            //$token = json_decode($data['_token']);
            $token = $data['_token'];

            if($canalSuscritoId != null && $canalAlQueSuscribeId != null) {
                $canalSuscritoEntity = $canalRepository->findOneBy(['id' => $canalSuscritoId]);
                $canalAlQueSuscribeEntity = $canalRepository->findOneBy(['id' => $canalAlQueSuscribeId]);
                if($canalAlQueSuscribeEntity != null && $canalSuscritoEntity != null) {
                    $suscripcionABorrar = $suscripcionRepository->findOneBy(['canalAlQueSuscribe' => $canalAlQueSuscribeEntity, 'canalQueSuscribe' => $canalSuscritoEntity]);
                    //if ($this->isCsrfTokenValid('delete'.$suscripcionABorrar->getId(), $token)) {
                    if ($this->isCsrfTokenValid('delete', $token)) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($suscripcionABorrar);
                        $entityManager->flush();

                        $videoId = json_decode($data ['idVideo']);
                        $videoEntity = $videoRepository->findOneBy(['id' => $videoId]);

                        $idSuscritosAlCanal = $this->getSuscritosACanal($canalAlQueSuscribeEntity);
                        $this->addFlash('success', 'Ya no estás suscrito a este canal');

                        if($data['idVideo'] != null) {
                            $videoEntity = $videoRepository->findOneBy(['id' => $videoId]);
                            return new JsonResponse([
                                'contenido' => $this->render('suscripcion/toggleSuscripcion.html.twig', [
                                    'idSuscritosAlCanal' => $idSuscritosAlCanal,
                                    'video' => $videoEntity
                                ])->getContent(),
                                'numeroSuscriptores' => \count($idSuscritosAlCanal)
                            ]);
                        }else {
                            return new JsonResponse([
                                'contenido' => $this->render('suscripcion/toggleSuscripcion.html.twig', [
                                    'idSuscritosAlCanal' => $idSuscritosAlCanal,
                                    'canal' => $canalAlQueSuscribeEntity
                                ])->getContent(),
                                'numeroSuscriptores' => \count($idSuscritosAlCanal)
                            ]);
                        }
                        
                    }
                }
                
                //$canalAlQueSuscribe;
                //$canalQueSuscribe
            }

        }
        

        //return $this->redirectToRoute('suscripcion_index');
    }

    public function getSuscritosACanal(Canal $canal) {
        $suscritosAlCanal = $canal->getSuscritosAMi();
        $idSuscritosAlCanal = [];
        foreach ($suscritosAlCanal as $suscritoAlCanal) {
            $idSuscritosAlCanal[] = $suscritoAlCanal->getCanalQueSuscribe()->getId();
        }

        return $idSuscritosAlCanal;
    }
}
