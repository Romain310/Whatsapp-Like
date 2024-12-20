<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_MAIL', fields: ['mail'])]
#[UniqueEntity(fields: ['mail'], message: 'There is already an account with this mail')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $mail = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $messages;

    /**
     * @var Collection<int, NotificationCommission>
     */
    #[ORM\OneToMany(targetEntity: NotificationCommission::class, mappedBy: 'user', cascade: ['persist'], orphanRemoval: true)]
    private Collection $notificationsCommissions;

    /**
     * @var Collection<int, NotificationCommissionTemporaire>
     */
    #[ORM\OneToMany(targetEntity: NotificationCommissionTemporaire::class, mappedBy: 'user', cascade: ['persist'], orphanRemoval: true)]
    private Collection $notificationsCommissionsTemporaires;

    /**
     * @var Collection<int, MessageLu>
     */
    #[ORM\OneToMany(targetEntity: MessageLu::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $messagesLus;

    #[ORM\Column(type: 'boolean')]
    private $actif = false;

    /**
     * @var Collection<int, CommissionTemporaire>
     */
    #[ORM\OneToMany(targetEntity: CommissionTemporaire::class, mappedBy: 'createur')]
    private Collection $commissionsTemporairesCreees; // false correspond à 0

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->notificationsCommissions = new ArrayCollection();
        $this->notificationsCommissionsTemporaires = new ArrayCollection();
        $this->messagesLus = new ArrayCollection();
        $this->commissionsTemporairesCreees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NotificationCommission>
     */
    public function getNotificationsCommissions(): Collection
    {
        return $this->notificationsCommissions;
    }

    public function addNotificationsCommission(NotificationCommission $notificationsCommission): static
    {
        if (!$this->notificationsCommissions->contains($notificationsCommission)) {
            $this->notificationsCommissions->add($notificationsCommission);
            $notificationsCommission->setUser($this);
        }

        return $this;
    }

    public function removeNotificationsCommission(NotificationCommission $notificationsCommission): static
    {
        if ($this->notificationsCommissions->removeElement($notificationsCommission)) {
            // set the owning side to null (unless already changed)
            if ($notificationsCommission->getUser() === $this) {
                $notificationsCommission->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NotificationCommissionTemporaire>
     */
    public function getNotificationsCommissionsTemporaires(): Collection
    {
        return $this->notificationsCommissionsTemporaires;
    }

    public function addNotificationsCommissionsTemporaire(NotificationCommissionTemporaire $notificationsCommissionsTemporaire): static
    {
        if (!$this->notificationsCommissionsTemporaires->contains($notificationsCommissionsTemporaire)) {
            $this->notificationsCommissionsTemporaires->add($notificationsCommissionsTemporaire);
            $notificationsCommissionsTemporaire->setUser($this);
        }

        return $this;
    }

    public function removeNotificationsCommissionsTemporaire(NotificationCommissionTemporaire $notificationsCommissionsTemporaire): static
    {
        if ($this->notificationsCommissionsTemporaires->removeElement($notificationsCommissionsTemporaire)) {
            // set the owning side to null (unless already changed)
            if ($notificationsCommissionsTemporaire->getUser() === $this) {
                $notificationsCommissionsTemporaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MessageLu>
     */
    public function getMessagesLus(): Collection
    {
        return $this->messagesLus;
    }

    public function addMessagesLu(MessageLu $messagesLu): static
    {
        if (!$this->messagesLus->contains($messagesLu)) {
            $this->messagesLus->add($messagesLu);
            $messagesLu->setUser($this);
        }

        return $this;
    }

    public function removeMessagesLu(MessageLu $messagesLu): static
    {
        if ($this->messagesLus->removeElement($messagesLu)) {
            // set the owning side to null (unless already changed)
            if ($messagesLu->getUser() === $this) {
                $messagesLu->setUser(null);
            }
        }

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection<int, CommissionTemporaire>
     */
    public function getCommissionsTemporairesCreees(): Collection
    {
        return $this->commissionsTemporairesCreees;
    }

    public function addCommissionsTemporairesCreee(CommissionTemporaire $commissionsTemporairesCreee): static
    {
        if (!$this->commissionsTemporairesCreees->contains($commissionsTemporairesCreee)) {
            $this->commissionsTemporairesCreees->add($commissionsTemporairesCreee);
            $commissionsTemporairesCreee->setCreateur($this);
        }

        return $this;
    }

    public function removeCommissionsTemporairesCreee(CommissionTemporaire $commissionsTemporairesCreee): static
    {
        if ($this->commissionsTemporairesCreees->removeElement($commissionsTemporairesCreee)) {
            // set the owning side to null (unless already changed)
            if ($commissionsTemporairesCreee->getCreateur() === $this) {
                $commissionsTemporairesCreee->setCreateur(null);
            }
        }

        return $this;
    }
}
