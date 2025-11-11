<?php

namespace App\Repository;

use App\Entity\Participante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participante>
 */
class ParticipanteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participante::class);
    }

    //    /**
    //     * @return Participante[] Returns an array of Participante objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Participante
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
  /*  public function findByEventoAndAdmin(object $evento, object $admin): array
    {
    $qb = $this->createQueryBuilder('p')
        ->innerJoin('p.inscripcions', 'i')
        ->andWhere('i.evento = :evento')
        ->setParameter('evento', $evento);

    // Si el admin NO es superadmin, filtramos por su localidad
    if (!in_array('ROLE_SUPER_ADMIN', $admin->getRoles())) {
        $qb->andWhere('p.localidad = :localidad')
           ->setParameter('localidad', $admin->getLocalidad());
    }

    return $qb->getQuery()->getResult();
  }
    */
  public function findByEventoAndAdmin(object $evento, object $admin): array
   {
    $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.inscripcions', 'i')
        ->leftJoin('p.evento', 'e')
        ->where('i.evento = :evento OR e = :evento')
        ->setParameter('evento', $evento);

    // Filtro por localidad si no es superadmin
    if (!in_array('ROLE_SUPER_ADMIN', $admin->getRoles())) {
        $qb->andWhere('p.localidad = :localidad')
           ->setParameter('localidad', $admin->getLocalidad());
    }

    $result = $qb->getQuery()->getResult();

    // Eliminar duplicados por ID
    $unicos = [];
    foreach ($result as $p) {
        $unicos[$p->getId()] = $p;
    }

    return array_values($unicos);
  }


}
