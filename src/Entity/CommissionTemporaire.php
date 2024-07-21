<?php

namespace App\Entity;

use App\Repository\CommissionTemporaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionTemporaireRepository::class)]
class CommissionTemporaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $cloture = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\ManyToMany(targetEntity: Message::class, mappedBy: 'commissionsTemporaires')]
    private Collection $messages;

    /**
     * @var Collection<int, NotificationCommissionTemporaire>
     */
    #[ORM\OneToMany(targetEntity: NotificationCommissionTemporaire::class, mappedBy: 'commissionTemporaire', orphanRemoval: true)]
    private Collection $notificationsUsers;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->notificationsUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): static
    {
        $this->debut = $debut;

        return $this;
    }

    public function getCloture(): ?\DateTimeInterface
    {
        return $this->cloture;
    }

    public function setCloture(\DateTimeInterface $cloture): static
    {
        $this->cloture = $cloture;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->addCommissionsTemporaire($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            $message->removeCommissionsTemporaire($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, NotificationCommissionTemporaire>
     */
    public function getNotificationsUsers(): Collection
    {
        return $this->notificationsUsers;
    }

    public function addNotificationsUser(NotificationCommissionTemporaire $notificationsUser): static
    {
        if (!$this->notificationsUsers->contains($notificationsUser)) {
            $this->notificationsUsers->add($notificationsUser);
            $notificationsUser->setCommissionTemporaire($this);
        }

        return $this;
    }

    public function removeNotificationsUser(NotificationCommissionTemporaire $notificationsUser): static
    {
        if ($this->notificationsUsers->removeElement($notificationsUser)) {
            // set the owning side to null (unless already changed)
            if ($notificationsUser->getCommissionTemporaire() === $this) {
                $notificationsUser->setCommissionTemporaire(null);
            }
        }

        return $this;
    }
}
