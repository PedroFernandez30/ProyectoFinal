<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Entity\Video;
use App\Entity\Canal;
use App\Form\ComentarioType;
use App\Repository\ComentarioRepository;
use App\Repository\CanalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


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
     * @Route("/list", name="comentario_list", methods={"GET", "POST"})
     */
    public function list(Request $request): Response
    {
        if($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            if($data['nivel'] == 'canal') {

                //Obtengo el canal
                $canal = new Canal();
                $canalRepository = $this->getDoctrine()
                ->getRepository(Canal::class);
                $canalEntity = $canalRepository->findOneBy(['id' => $data['canalComentado']]);

                //Obtengo los comentarios realizados en ese canal
                $comentario = new Comentario();
                $comentarioRepository = $this->getDoctrine()
                ->getRepository(Comentario::class);
                $comentariosFindCanal = $comentarioRepository->findBy(['canalComentado' => $canalEntity]);
                $comentariosEnCanal =  $comentariosFindCanal == null ? null : $comentariosFindCanal;

                return new JsonResponse($this->render('comentario/viewComments.html.twig', [
                    'comentarios' => $comentariosEnCanal,
                    'nivel' => 'canal',
                    'canal' => $canalEntity,
                ])->getContent());
            }
        }
        throw Exception;
    }

    /**
     * @Route("/new", name="comentario_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserInterface $canalActivo): Response
    {
        $comentario = new Comentario();
        $comentarioRepository = $this->getDoctrine()
        ->getRepository(Comentario::class);

        $videoRepository = $this->getDoctrine()
        ->getRepository(Video::class);
        if($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            $entityManager = $this->getDoctrine()->getManager();
            $comentario->setContenido($data['contenido']);
            //$comentario->setContenido(json_decode($data['videoComentado']));
            $comentario->setFechaComentario(new \DateTime("now"));
            $comentario->setCanalQueComenta($canalActivo);

            $videoComentado = json_decode($data['videoComentado']);
            $canalComentado = json_decode($data['canalComentado']);

            if($videoComentado != null) {
                //throw Exception();
                $videoEntity = $videoRepository->findOneBy(['id' => $videoComentado]);
                $comentario->setIdVideo($videoEntity);
                $entityManager->persist($comentario);
                $entityManager->flush();

                $comentariosEnVideo = $comentarioRepository->findBy(['idVideo' => $videoEntity]);
                $universalController = new UniversalController();
                $comentariosArray = $universalController->crearArray($comentariosEnVideo);
                
                
            }else if($canalComentado != null) {
                $canalRepository = $this->getDoctrine()
                ->getRepository(Canal::class);
                $canalEntity = $canalRepository->findOneBy(['id' => $canalComentado]);
                $comentario->setCanalComentado($canalEntity);
                $entityManager->persist($comentario);
                $entityManager->flush();

                $comentariosEnCanal = $comentarioRepository->findBy(['canalComentado' => $canalEntity]);
                $universalController = new UniversalController();
                $comentariosArray = $universalController->crearArray($comentariosEnCanal);

                

            }

            $arrData = $comentariosArray;
            return new JsonResponse($arrData);
        }
        
        /*$form = $this->createForm(ComentarioType::class, $comentario);
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
        ]);*/
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
