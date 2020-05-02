<?php

namespace App\Controller;

use App\Entity\Suscripcion;
use App\Form\SuscripcionType;
use App\Repository\SuscripcionRepository;
use App\Repository\CanalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function new(Request $request, UserInterface $canalActivo): Response
    {
        $suscripcion = new Suscripcion();
        $form = $this->createForm(SuscripcionType::class, $suscripcion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $suscripcion->setCanalQueSuscribe($canalActivo);
            $entityManager->persist($suscripcion);
            $entityManager->flush();

            return $this->redirectToRoute('suscripcion_index');
        }

        return $this->render('suscripcion/new.html.twig', [
            'suscripcion' => $suscripcion,
            'form' => $form->createView(),
        ]);
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
     * @Route("/{id}", name="suscripcion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Suscripcion $suscripcion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suscripcion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($suscripcion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('suscripcion_index');
    }
}
