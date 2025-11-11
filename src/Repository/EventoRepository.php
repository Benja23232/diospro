<?php

namespace App\Repository;

use App\Entity\Evento;
use App\Entity\Participante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evento>
 *
 * @method Evento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evento[]    findAll()
 * @method Evento[]    findBy(array $criteria, array $orderBy = null)
 */
class EventoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evento::class);
    }
    
    /**
     * Devuelve un array de objetos Participante para un Evento dado.
     *
     * @return Participante[]
     */
    public function findParticipantesByEvento(int $eventoId): array
    {
        // La consulta DQL ahora hace un JOIN a través de la entidad Inscripcion.
        // Esto es necesario porque la relación directa se eliminó.
        // 'e.inscripcions' es la relación OneToMany en la entidad Evento.
        // 'i.participante' es la relación ManyToOne en la entidad Inscripcion.
        return $this->createQueryBuilder('e')
            ->select('p')
            ->join('e.inscripcions', 'i')
            ->join('i.participante', 'p')
            ->where('e.id = :eventoId')
            ->setParameter('eventoId', $eventoId)
            ->getQuery()
            ->getResult();
    }
}
