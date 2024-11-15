<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"text")]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnvoi = null;

    /**
     * @var Collection<int, Commission>
     */
    #[ORM\ManyToMany(targetEntity: Commission::class, inversedBy: 'messages')]
    private Collection $commissions;

    /**
     * @var Collection<int, CommissionTemporaire>
     */
    #[ORM\ManyToMany(targetEntity: CommissionTemporaire::class, inversedBy: 'messages')]
    private Collection $commissionsTemporaires;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, MessageLu>
     */
    #[ORM\OneToMany(targetEntity: MessageLu::class, mappedBy: 'message', cascade: ['persist'])]
    private Collection $usersReader;

    public function __construct()
    {
        $this->commissions = new ArrayCollection();
        $this->commissionsTemporaires = new ArrayCollection();
        $this->usersReader = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateEnvoi(): ?\DateTimeInterface
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(\DateTimeInterface $dateEnvoi): static
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    /**
     * @return Collection<int, Commission>
     */
    public function getCommissions(): Collection
    {
        return $this->commissions;
    }

    public function addCommission(Commission $commission): static
    {
        if (!$this->commissions->contains($commission)) {
            $this->commissions->add($commission);
        }

        return $this;
    }

    public function removeCommission(Commission $commission): static
    {
        $this->commissions->removeElement($commission);

        return $this;
    }

    /**
     * @return Collection<int, CommissionTemporaire>
     */
    public function getCommissionsTemporaires(): Collection
    {
        return $this->commissionsTemporaires;
    }

    public function addCommissionsTemporaire(CommissionTemporaire $commissionsTemporaire): static
    {
        if (!$this->commissionsTemporaires->contains($commissionsTemporaire)) {
            $this->commissionsTemporaires->add($commissionsTemporaire);
        }

        return $this;
    }

    public function removeCommissionsTemporaire(CommissionTemporaire $commissionsTemporaire): static
    {
        $this->commissionsTemporaires->removeElement($commissionsTemporaire);

        return $this;
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

    /**
     * @return Collection<int, MessageLu>
     */
    public function getUsersReader(): Collection
    {
        return $this->usersReader;
    }

    public function addUserReader(MessageLu $user): static
    {
        if (!$this->usersReader->contains($user)) {
            $this->usersReader->add($user);
            $user->setMessage($this);
        }

        return $this;
    }

    public function removeUser(MessageLu $user): static
    {
        if ($this->usersReader->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getMessage() === $this) {
                $user->setMessage(null);
            }
        }

        return $this;
    }
}
