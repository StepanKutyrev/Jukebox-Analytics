<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PlaybackLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlaybackLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlaybackLog::class);
    }

    public function save(PlaybackLog $log, bool $flush = true): void
    {
        $this->getEntityManager()->persist($log);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTopTracks(int $limit = 3): array
    {
        return $this->createQueryBuilder('pl')
            ->select('t.id as trackId, t.title as title, COUNT(pl.id) as playCount')
            ->join('pl.track', 't')
            ->groupBy('t.id')
            ->orderBy('playCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
