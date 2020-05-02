<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Form\ComentarioType;
use App\Repository\ComentarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/comentario")
 */
class ComentarioController extends AbstractController
{
    /**
     * @Route("/", name="comentario_index", methods={"GET"})
     */
    public function index(ComentarioRepository $comentarioRepository): Response
    {
        return $this->render('comentario/index.html.twig', [
            'comentarios' => $comentarioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comentario_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserInterface $canalActivo): Response
    {
        $comentario = new Comentario();
        $form = $this->createForm(ComentarioType::class, $comentario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comentario->setCanalQueComenta($canalActivo);
            $entityManager->persist($comentario);
            $entityManager->flush();

            return $this->redirectToRoute('comentario_index');
        }

        return $this->render('comentario/new.html.twig', [
            'comentario' => $comentario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comentario_show", methods={"GET"})
     */
    public function show(Comentario $comentario): Response
    {
        return $this->render('comentario/show.html.twig', [
            'comentario' => $comentario,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comentario_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comentario $comentario): Response
    {
        $form = $this->createForm(ComentarioType::class, $comentario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comentario_index');
        }

        return $this->render('comentario/edit.html.twig', [
            'comentario' => $comentario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comentario_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comentario $comentario): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comentario->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comentario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comentario_index');
    }
}
