<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use getID3;

class UniversalController extends AbstractController
{
    public function subidaVideo($canalId, $videoId, $form, $video = false, $fotoPerfil = false) :string
    {
        if($fotoPerfil) {
            
            //Guardar foto perfil si la ha subido

             /** @var UploadedFile $fotoPerfil */
             $fotoPerfil = $form->get('fotoPerfil')->getData();

             // this condition is needed because the 'brochure' field is not required
             // so the PDF file must be processed only when a file is uploaded
             if ($fotoPerfil != 'imgPerfil/profile.jpg') {
                 // Move the file to the directory where brochures are stored
                try {
                    $fotoPerfil->move(
                        $this->getParameter('imagenesPerfil'),
                        $canalId
                    );

                     
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

    public function crearArray($comentarios) {
        $comentariosArray = [];
        //Creo el canal que comenta
        

        //Relleno el array con mi objeto comentario
        foreach ($comentarios as $comentario) {
            
            $canalQueComenta = (object) [
                'id' => $comentario->getCanalQueComenta()->getId(),
                'nombre' => $comentario->getCanalQueComenta()->getNombreCanal()
            ];


            //Creo el comentario
            $comentarioObject = (object) [
                'id' => $comentario->getId(),
                'contenido' => $comentario->getContenido(),
                'fechaPublicacion' => $comentario->getFechaComentario()->format('d/m/Y'),
                'canalQueComenta' => $canalQueComenta
            ];
            $comentariosArray[] = $comentarioObject;
        }
        return $comentariosArray;
    }
}
