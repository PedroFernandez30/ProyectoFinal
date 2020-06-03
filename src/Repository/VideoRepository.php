<?php

namespace App\Repository;

use App\Entity\Video;
use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    // /**
    //  * @return Video[] Returns an array of Video objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Video
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
     */

     public function findSimilarVideos($valor, $em) {
        $videosBySimilarName = $this->findBySimilarName($valor);
        foreach($videosBySimilarName as $videoName) {
            \dump($videoName->getFechaPublicacion()->format('d/m/Y'));
        }
        dump("Salto");
        $videosBySimilarCategoria = $this->findBySimilarCategoria($valor, $em);
        foreach($videosBySimilarCategoria as $videoCategoria) {
            \dump($videoCategoria->getFechaPublicacion()->format('d/m/Y'));
        }
        //Sacar los repetidos , juntarlos en un array, y devolver ese array

        dump("saco vídeos resultantes");
        $videosResultantes = array_unique(array_merge($videosBySimilarName, $videosBySimilarCategoria));
        foreach($videosResultantes as $videoResultante) {
            \dump($videoResultante->getFechaPublicacion()->format('d/m/Y'));
        }

        /*function sortFunction( $a, $b ) {
            return strtotime($a->getFechaPublicacion()->format('d/m/Y')) - strtotime($b->getFechaPublicacion()->format('d/m/Y'));
        }
        usort($videosResultantes, function($a, $b) {
            return strtotime($a->getFechaPublicacion()->format('d/m/Y')) - strtotime($b->getFechaPublicacion()->format('d/m/Y'));
        });*/
        //\dump($videosResultantes);
        return $videosResultantes;

     }

     

    public function findBySimilarName($valor) {
        return $this->createQueryBuilder('v')
            ->andWhere('LOWER(v.titulo) LIKE LOWER(:val)')
            ->orderBy('v.fechaPublicacion','DESC')
            ->setParameter('val', '%' . $valor . '%')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySimilarCategoria($valor, $em) {
        
        return $this->createQueryBuilder('v')
            ->join('v.idCategoria', 'c')
            ->andWhere('LOWER(c.nombre) LIKE LOWER(:val)')
            ->orderBy('v.fechaPublicacion','DESC')
            ->setParameter('val', '%' . $valor . '%')
            ->getQuery()
            ->getResult()
        ;
    }


    //Encuentra los vídeos anteriores a la fecha introducida (día, semana, mes o año)
    public function findByFechaPublicacion($fechaRestada) {
        return $this->createQueryBuilder('v')
            ->andWhere('v.fechaPublicacion > :fecha')
            ->orderBy('v.fechaPublicacion','DESC')
            ->setParameter('fecha', $fechaRestada)
            ->getQuery()
            ->getResult()
        ;
     }


    //Encuentra los vídeos relacionados cuya categoría sea la misma que la del vídeo que se está visualizando
    public function findVideosRelacionados($categoria, $idVideo)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.idCategoria = :categoria')
            ->andWhere('v.id != :idVideo')
            ->orderBy('v.fechaPublicacion', 'DESC')
            ->setParameter('categoria', $categoria)
            ->setParameter('idVideo', $idVideo)
            ->getQuery()
            ->getResult()
        ;
    }

   
}
