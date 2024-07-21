<?php

namespace App\Entity;

use App\Repository\NotificationCommissionTemporaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationCommissionTemporaireRepository::class)]
class NotificationCommissionTemporaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notificationsCommissionsTemporaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'notificationsUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CommissionTemporaire $commissionTemporaire = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCommissionTemporaire(): ?CommissionTemporaire
    {
        return $this->commissionTemporaire;
    }

    public function setCommissionTemporaire(?CommissionTemporaire $commissionTemporaire): static
    {
        $this->commissionTemporaire = $commissionTemporaire;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
