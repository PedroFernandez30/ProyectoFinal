<?php

namespace App\Repository;

use App\Entity\Video;
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

     public function findSimilarVideos($valor) {
        $videosBySimilarName = $this->findVideoBySimilarName($valor);
        $videosBySimilarCategoria = $this->findVideosBySimilarCategoria($valor);
        //Sacar los repetidos , juntarlos en un array, y devolver ese array

     }

    public function findVideoByName() {
        return $this->createQueryBuilder('v')
            ->andWhere('v.idCategoria = :categoria')
            ->andWhere('v.id != :idVideo')
            ->setParameter('categoria', $categoria)
            ->setParameter('idVideo', $idVideo)
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
            ->setParameter('categoria', $categoria)
            ->setParameter('idVideo', $idVideo)
            ->getQuery()
            ->getResult()
        ;
    }

   
}
