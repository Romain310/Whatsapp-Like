<?php

namespace App\Entity;

use App\Repository\CommissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionRepository::class)]
class Commission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\ManyToMany(targetEntity: Message::class, mappedBy: 'commissions')]
    private Collection $messages;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $icone = null;

    /**
     * @var Collection<int, NotificationCommission>
     */
    #[ORM\OneToMany(targetEntity: NotificationCommission::class, mappedBy: 'commission', orphanRemoval: true)]
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
            $message->addCommission($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            $message->removeCommission($this);
        }

        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone): static
    {
        $this->icone = $icone;

        return $this;
    }

    /**
     * @return Collection<int, NotificationCommission>
     */
    public function getNotificationsUsers(): Collection
    {
        return $this->notificationsUsers;
    }

    public function addNotificationsUser(NotificationCommission $notificationsUser): static
    {
        if (!$this->notificationsUsers->contains($notificationsUser)) {
            $this->notificationsUsers->add($notificationsUser);
            $notificationsUser->setCommission($this);
        }

        return $this;
    }

    public function removeNotificationsUser(NotificationCommission $notificationsUser): static
    {
        if ($this->notificationsUsers->removeElement($notificationsUser)) {
            // set the owning side to null (unless already changed)
            if ($notificationsUser->getCommission() === $this) {
                $notificationsUser->setCommission(null);
            }
        }

        return $this;
    }
}
