<?php

namespace App\Controller;

use App\Entity\Canal;
use App\Entity\Video;
use App\Form\CanalType;
use App\Form\VideoType;
use App\Form\CabeceraType;
use App\Repository\CanalRepository;
use App\Repository\ComentarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Controller\UniversalController;


/**
 * @Route("/canal")
 */
class CanalController extends AbstractController
{
    /**
     * @Route("/", name="canal_index", methods={"GET"})
     */
    public function index(CanalRepository $canalRepository): Response
    {
        return $this->render('canal/index.html.twig', [
            'canals' => $canalRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="canal_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $canal = new Canal();
        $form = $this->createForm(CanalType::class, $canal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($canal);
            $entityManager->flush();

            return $this->redirectToRoute('canal_index');
        }

        return $this->render('canal/new.html.twig', [
            'canal' => $canal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route({"es": "/ver/{id}","en": "/view/{id}"}, name="canal_show", methods={"GET","POST"})
     */
    public function show(Canal $canal): Response
    {
        //$video = new Video();
        //$form = $this->createForm(VideoType::class, $video);
        $suscripcionController = new SuscripcionController();
        $idSuscritosAlCanal = $suscripcionController->getSuscritosACanal($canal);

        return $this->render('canal/show.html.twig', [
            'canal' => $canal,
            'comentario' => 'canal',
            'idSuscritosAlCanal' => $idSuscritosAlCanal,
            'extiende' => 'true'
            //'video' => $video,
            //'form' => $form->createView()
        ]);
    }

    /**
     * @Route({"es": "/{id}/editar","en": "/{id}/edit"}, name="canal_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Canal $canal, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        //$canal = new
        $form = $this->createForm(CanalType::class, $canal, [
            'action' => $this->generateUrl('canal_edit', ['id' => $canal->getId()])
        ]);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest()) {
            
            if($form->isSubmitted() && $form->isValid()) {
                
                $password = $form->get('plainPassword')->getData();
                if($password != '------') {
                    $canal->setPassword($passwordEncoded = $passwordEncoder->encodePassword(
                        $canal,
                        $password
                    ));
        
                }
                
                //\dump($form->get('fotoPerfil')->getData());
                $rutaImgPerfil = '';
                if($form->get('fotoPerfil')->getData() != 'imgPerfil/profile.jpg') {
                    $universalController = new UniversalController();
                    $rutaImgPerfil = $universalController->subidaArchivo($canal->getId(),'', $form, false, true);
                }
                

                $this->getDoctrine()->getManager()->persist($canal);

                $this->getDoctrine()->getManager()->flush();

                $mensaje = 'Datos actualizados con éxito';
                
                $nombreCanal = $canal->getNombreCanal();
                
                
                //$form->handleRequest($request);
                return new JsonResponse([
                    'code' => 'success',
                    'nombreCanal' => $nombreCanal,
                    'mensaje' => $mensaje,
                    'rutaImgPerfil' => $rutaImgPerfil
                ]);

            } else {
                $universalController = new UniversalController();
                $errores = $universalController->getArrayErrores($form);
                
                return new JsonResponse([
                    'code' => 'error',
                    'errores' => $errores
                ]);
            }
            
            
            

        }
        

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('canal_index');
        }

        

        /*$formCabecera = $this->createForm(CabeceraType::class);
        $formCabecera->handleRequest($request);

        if ($formCabecera->isSubmitted() && $formCabecera->isValid()) {
            //$this->getDoctrine()->getManager()->flush();
            $dataCabecera = $formCabecera->getData();

            return $this->redirectToRoute('canal_index', [
                'dataCabecera' => $dataCabecera
            ]);
        }*/

        return $this->render('canal/edit.html.twig', [
            'canal' => $canal,
            'form' => $form->createView(),
            'extiende' => 'true'
        ]);
    }

    public function convertToObject($array) {
        $object = new \stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = convertToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }

    /**
     * @Route("/delete/{id}", name="canal_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Canal $canal, ComentarioRepository $comentarioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$canal->getId(), $request->request->get('_token'))) {
            try {
                $this->get('security.token_storage')->setToken(null);
                $request->getSession()->invalidate();

                //QUito el dueño de los comentarios a los comentarios realizados por el canal a borrar
                $entityManager = $this->getDoctrine()->getManager();

                $comentariosCanalABorrar = $comentarioRepository->findBy(['canalQueComenta' => $canal]);
                foreach($comentariosCanalABorrar as $comentario) {
                    $comentario->setCanalQueComenta(null);
                    $entityManager->persist($comentario);
                }
    
                
                $entityManager->remove($canal);
                $entityManager->flush();

                //QUito el dueño de los comentarios a los comentarios realizados por el canal a borrar
               

                $this->addFlash('success', 'Cuenta borrada con éxito');
                return $this->redirectToRoute('video_index');
            }catch(\Exception $e) {
                $this->addFlash('error', 'Hubo un error al tratar de borrar la cuenta');
                return $this->redirectToRoute('video_index');
            }
        }
                

        
    }

    
}
