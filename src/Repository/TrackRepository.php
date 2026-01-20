<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Track;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TrackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Track::class);
    }

    public function save(Track $track, bool $flush = true): void
    {
        $this->getEntityManager()->persist($track);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
