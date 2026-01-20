<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PlaybackLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaybackLogRepository::class)]
#[ORM\Table(name: 'playback_log')]
class PlaybackLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Track::class, inversedBy: 'playbackLogs')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Track $track;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $playedAt;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $amountPaid;

    public function __construct()
    {
        $this->playedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrack(): Track
    {
        return $this->track;
    }

    public function setTrack(Track $track): self
    {
        $this->track = $track;
        return $this;
    }

    public function getPlayedAt(): \DateTimeImmutable
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTimeImmutable $playedAt): self
    {
        $this->playedAt = $playedAt;
        return $this;
    }

    public function getAmountPaid(): string
    {
        return $this->amountPaid;
    }

    public function setAmountPaid(string $amountPaid): self
    {
        $this->amountPaid = $amountPaid;
        return $this;
    }
}
