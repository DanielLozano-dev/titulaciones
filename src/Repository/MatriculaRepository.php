<?php

namespace App\Repository;

use App\Entity\Matricula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matricula>
 */
class MatriculaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matricula::class);
    }

    //    /**
    //     * @return Matricula[] Returns an array of Matricula objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Matricula
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllByAlumnoId($alumnoId)
    {
        return $this->createQueryBuilder('m')
            ->where('m.user = :alumnoId')
            ->setParameter('alumnoId', $alumnoId)
            ->getQuery()
            ->getResult();
    }

    public function desactivarMatriculasPorUsuarioId(int $usuarioId): void
    {
        $this->getEntityManager()
            ->createQuery('
            UPDATE App\Entity\Matricula m
            SET m.activa = false
            WHERE m.user = :usuarioId
        ')
            ->setParameter('usuarioId', $usuarioId)
            ->execute();
    }

    public function desactivarMatriculasPorTitulacion(int $titulacionId): void
    {
        $this->getEntityManager()
            ->createQuery('
            UPDATE App\Entity\Matricula m
            SET m.activa = false
            WHERE m.titulacion = :titulacion
        ')
            ->setParameter('titulacion', $titulacionId)
            ->execute();
    }

    public function desactivarMatriculasPorCentro(int $centroId): void
    {
        $this->getEntityManager()
            ->createQuery('
            UPDATE App\Entity\Matricula m
            SET m.activa = false
            WHERE m.centro = :centro
        ')
            ->setParameter('centro', $centroId)
            ->execute();
    }


}
