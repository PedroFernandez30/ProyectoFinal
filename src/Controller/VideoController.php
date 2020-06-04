<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use App\Repository\CanalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\UniversalController;



class VideoController extends AbstractController
{
    /**
     * @Route({"es": "/inicio","en": "/index"}, name="video_index", methods={"GET"})
     */
    public function index(VideoRepository $videoRepository): Response
    {
        return $this->render('video/index.html.twig', [
            //'videos' => $videoRepository->findBy([],null, 8, 0),
            'videos' => $videoRepository->findAll(),
            'extiende' => 'true',
            'index' => true
        ]);
    }

    /**
     * @Route("/list", name="video_list", methods={"GET", "POST"})
     */
    public function list(Request $request, VideoRepository $videoRepository, CanalRepository $canalRepository): Response
    {
        if($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            $canalId = json_decode($data['idCanal']);
            if($canalId != null) {
                $canalEntity = $canalRepository->findOneBy(['id' => $canalId]);
                $videosFromCanal = $videoRepository->findBy(['idCanal' => $canalEntity], ['fechaPublicacion' => 'DESC']);
                return new JsonResponse($this->render('video/index.html.twig', [
                    'videos' => $videosFromCanal,
                    'extiende' => false
                ])->getContent());
            } else {
                $start = \json_decode($data['start']) *4;
                $videosList = $videoRepository->findBy([], ['fechaPublicacion' => 'DESC'], 4, $start);
                return new JsonResponse($this->render('video/index.html.twig', [
                    'videos' => $videosList,
                ])->getContent());
            }
        }
        
        
    }

    /**
     * @Route({"es": "/nuevo/","en": "/new/"}, name="video_new", methods={"GET","POST"})
     */
    public function new(VideoRepository $videoRepository, Request $request, UserInterface $canalActivo):Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video, [
            'action' => $this->generateUrl('video_new'),
        ]);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $video->setIdCanal($canalActivo);
                $video->setFechaPublicacion(new \Datetime());
                $entityManager->persist($video);
                $entityManager->flush();
                //Subida de vídeo y miniatura
                $universalController = new UniversalController();
                $videoLength = $universalController-> subidaArchivo($canalActivo->getId(), $video->getId(), $form, true);
    
                $video->setDuracion($videoLength);
                $entityManager->persist($video);
                $entityManager->flush();

                $videosFromCanal = $videoRepository->findBy(['idCanal' => $canalActivo], ['fechaPublicacion' => 'DESC']);

                $mensaje = 'Vídeo subido correctamente';
                /*unset($entity);
                unset($video);
                $video = new Video();
                $form = $this->createForm(VideoType::class, $video, [
                    'action' => $this->generateUrl('video_new'),
                ]);*/
                return new JsonResponse([
                    'code' => 'success',
                    'contenido' => $this->render('video/index.html.twig', [
                        'videos' => $videosFromCanal,
                        'extiende' => false
                    ])->getContent(),
                    'mensaje' => $mensaje

                ]);
                //return $this->redirectToRoute('video_index');
            } else {
                $universalController = new UniversalController();
                $errores = $universalController->getArrayErrores($form);
                
                return new JsonResponse([
                    'code' => 'error',
                    'errores' => $errores
                ]);
            }
        }
        

        return $this->render('video/new.html.twig', [
            'video' => $video,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="video_show", methods={"GET"})
     */
    public function show(VideoRepository $videoRepository, Video $video): Response
    {
        $suscritosAlCanal = $video->getIdCanal()->getSuscritosAMi();
        $idSuscritosAlCanal = [];
        foreach ($suscritosAlCanal as $suscritoAlCanal) {
            $idSuscritosAlCanal[] = $suscritoAlCanal->getCanalQueSuscribe()->getId();
        }

        //$videosCategoria = $videoRepository->findBy(['idCategoria' => $video->getIdCategoria()]);
        $videosCategoria = $videoRepository->findVideosRelacionados($video->getIdCategoria(), $video->getId());

        return $this->render('video/show.html.twig', [
            'video' => $video,
            'idSuscritosAlCanal' => $idSuscritosAlCanal,
            'comentario' => 'video',
            'videosCategoria' => $videosCategoria
        ]);
    }

    /**
     * @Route("/{id}/edit", name="video_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Video $video): Response
    {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('video_index');
        }

        return $this->render('video/edit.html.twig', [
            'video' => $video,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="video_delete", methods={"DELETE"})
     */
    public function delete(Request $request, VideoRepository $videoRepository, CanalRepository $canalRepository, Video $video): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()) {
            $datos = $request->request->all();
            $token = $datos['_token'];
            $canalId = $datos['idCanal'];
            $videoId = $datos['idVideo'];

            if ($this->isCsrfTokenValid('delete'.$video->getId(), $token)) {
                $videoEntity = $videoRepository->findOneBy(['id' => $videoId]);
                
                try {
                    $rutaVideosYMiniaturas = $this->getParameter('videosYMiniaturas');
                    //borro el vídeo
                    \unlink($rutaVideosYMiniaturas.$canalId.'\videos\\'.$videoId);

                    //borro la miniatura
                    \unlink($rutaVideosYMiniaturas.$canalId.'\miniaturas\\'.$videoId);

                    //Borro la entidad Vídeo
                    $entityManager->remove($videoEntity);
                    $entityManager->flush();

                    

                    $canalEntity = $canalRepository->findOneBy(['id' => $canalId]);
                    $listaVideos = $videoRepository->findBy(['idCanal' => $canalEntity], ['id' => 'DESC']);

                    $mensajeExito = 'Vídeo borrado correctamente';

                    return $this->json([
                        'code' => 'success',
                        'mensaje' => $mensajeExito,
                        'contenido' => $this->render('video/index.html.twig', [
                            'videos' => $listaVideos, 
                            'extiende' => 'false',
                            'borrar' => true
                        ])->getContent()
                    ]);

                }catch(\Exception $e) {
                    $mensajeError = 'Hubo un error al tratar de borrar el archivo';
                    return $this->json([
                        'code' => 'error',
                        'mensaje' => $mensajeError
                    ]);
                }
                
            }
    
        } else {
            $entityManager->remove($video);
            $entityManager->flush();
            $this->addFlash('success', 'Vídeo borrado correctamente');
            return $this->redirectToRoute('video_index');
        }
        
    }
}
