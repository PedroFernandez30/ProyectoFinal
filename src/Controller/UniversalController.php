<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CanalRepository;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use getID3;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class UniversalController extends AbstractController
{

    
    /**
     * @Route("/{_locale}/{previo}", requirements={"_locale"="en|fr|es"}, name="traducir")
     */
    public function traducir(Request $request, $_locale, $previo)
    {
        //$previo = $_REQUEST['previo'];
        //{_locale}
        //, requirements={"_locale"="en|fr|es"}
        // lógica para determinar el $locale
        //$locale = $request->getLocale();
        $request->getSession()->set('_locale', $_locale);
        //return $this->redirectToRoute($previo);
        return $this->redirectToRoute('video_index',[
            //'_locale' => $_locale
        ]);
        //return $this->generateUrl($previo);
        
        
    }

     /**
     * @Route("/filtrar/", name="filtrar")
     */
    public function filtrar (VideoRepository $videoRepository, Request $request) {
        if($request->isXmlHttpRequest()) {
            $datos = $request->request->all();
            $diasARestar = json_decode($datos['diasARestar']);
        }
        $segundos = strToTime("now") - (86400 * $diasARestar);
        
        $fechaRestada = new \DateTime(date('Y/m/d h:i:s', $segundos));

        $videosFiltradosPorFecha = $videoRepository->findByFechaPublicacion($fechaRestada);
        //dump(count($videosFiltradosPorFecha));
        //\dump($videosFiltradosPorFecha);
        foreach($videosFiltradosPorFecha as $videoFiltrado) {
            //\dump($videoFiltrado->getFechaPublicacion());
        }
        
        return $this->json([
            'code' => 'success',
            //'videosFiltrados' => $videosFiltradosPorFecha,
            'contenido' => $this->render('buscador/listaVideosFiltrados.html.twig', [
                'videosFiltrados' => $videosFiltradosPorFecha,
            ])
        ]);
        
        //where v.fechaPublicacion < 
    }


     /**
     * @Route("/buscar/", name="buscar")
     */
    public function buscar(Request $request, CanalRepository $canalRepository, VideoRepository $videoRepository) {
        if($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            $valor = $data['value'];
            $canalesAll = $canalRepository->findAll();
            $canales = $canalRepository->findCanalesByNombreCanal($valor);
            $videos = $videoRepository->findSimilarVideos($valor, $this->getDoctrine()->getManager());
            $videosLikeArray = [];
            $canalesLikeArray = [];

            foreach($canales as $canal) {
                $canalesLikeArray[] = [
                    'id' => $canal->getId(),
                    'nombreCanal' => $canal->getNombreCanal(),
                    'suscriptores' => \count($canal->getSuscritosAMi())
                ];
            }

            foreach($videos as $video) {
                $videosLikeArray[] = [
                    'id' => $video->getId(),
                    'titulo' => $video->getTitulo(),
                    'fechaPublicacion' => $video->getFechaPublicacion(),
                    'idCanal' => $video->getIdCanal()
                ];
            }

            $respuesta = [
                'code' => 'success',
                'contenido' => $this->render('buscador/lista.html.twig', [
                    'canales' => $canalesLikeArray,
                    'videos' => $videosLikeArray
                ])->getContent()
            ];
            return new JsonResponse($respuesta);
        } 
        
    }


    public function getArrayErrores($form) {
        $camposForm = $form->all();        
        $errores = [];
        
        foreach($camposForm as $key => $value ) {
            $errores[$key] = $value->getErrors(true, false)->__toString();
        }

        return $errores;
    }

    //Sube las imágenes de perfil, los vídeos y las miniaturas
    public function subidaArchivo($canalId, $videoId, $form, $video = false, $fotoPerfil = false) :?string
    {
        if($fotoPerfil) {
            dump($canalId);
            
            //Guardar foto perfil si la ha subido

             /** @var UploadedFile $fotoPerfil */
             $fotoPerfil = $form->get('fotoPerfil')->getData();
             
             //\dump($fotoPerfil);

             // this condition is needed because the 'brochure' field is not required
             // so the PDF file must be processed only when a file is uploaded
             if ($fotoPerfil != 'imgPerfil/profile.jpg') {
                
                 // Move the file to the directory where brochures are stored
                try {

                    //EL GET PARAMETER NO FUNCIONA!!!!!!
                    $fotoPerfil->move(
                        //$this->getParameter('imagenesPerfil'),
                        './imgPerfil/',
                        $canalId
                    );
                    // Absolute path
                    //$ruta =  $package->getUrl('/image.png');
                    // result: /image.png

                    $package = new Package(new EmptyVersionStrategy());
                    // Relative path
                    $rutaImgPerfil =  $package->getUrl('/imgPerfil/'.$canalId);
                    // result: image.png
                    return $rutaImgPerfil;

                     
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }


            
        } else if($video) {
            $miniatura = $form->get('miniatura')->getData();
            try {
                $miniatura->move(
                    "./uploads/{$canalId}/miniaturas/",
                    $videoId
                );
                
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }


            $videoASubir = $form->get('video')->getData();
            try {
                if($videoASubir != null) {
                    $videoASubir->move(
                        "./uploads/{$canalId}/videos/",
                        $videoId
                    );
                    $getID3 = new getID3();
                    $rutaVideo = "./uploads/{$canalId}/videos/{$videoId}";

                    $video_file = $getID3->analyze($rutaVideo);

                    // Get the duration in string, e.g.: 4:37 (minutes:seconds)
                    //$videoLength = $video_file['playtime_seconds'];
                    $videoSeconds = $video_file['playtime_seconds'];
                    $videoLength = $this->PlaytimeString($videoSeconds);
                    return $videoLength;
                    
                    //Guardamos el vídeo
                }
                
                

            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

        // do anything else you need here, like send an email
        }
       
    }

    /**
	 * @param int $seconds
	 *
	 * @return string
	 */
    public static function PlaytimeString($seconds):string 
    {
		$sign = (($seconds < 0) ? '-' : '');
		$seconds = round(abs($seconds));
		$H = (int) floor( $seconds                            / 3600);
		$M = (int) floor(($seconds - (3600 * $H)            ) /   60);
		$S = (int) round( $seconds - (3600 * $H) - (60 * $M)        );
		return $sign.($H ? $H.':' : '').($H ? str_pad($M, 2, '0', STR_PAD_LEFT) : intval($M)).':'.str_pad($S, 2, 0, STR_PAD_LEFT);
    }


    //Devuelve un array con los datos necesarios para la respuesta AJAX de los comentarios de un vídeo o de un canal

   /* public function crearArray($comentarios) {
        $comentariosArray = [];
        //Creo el canal que comenta
        

        //Relleno el array con mi objeto comentario
        foreach ($comentarios as $comentario) {
            
            $canalQueComenta = (object) [
                'id' => $comentario->getCanalQueComenta()->getId(),
                'nombreCanal' => $comentario->getCanalQueComenta()->getNombreCanal()
            ];


            //Creo el comentario
            $comentarioObject = (object) [
                'id' => $comentario->getId(),
                'contenido' => $comentario->getContenido(),
                'fechaComentario' => $comentario->getFechaComentario(),
                'canalQueComenta' => $canalQueComenta
            ];
            $comentariosArray[] = $comentarioObject;
        }
        return $comentariosArray;
    }*/
}
