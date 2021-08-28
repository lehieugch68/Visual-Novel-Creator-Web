<?php

namespace App\Entity;

use App\Repository\AccountRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRoleRepository::class)
 */
class AccountRole
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Account::class, mappedBy="roleid")
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=Account::class, mappedBy="role")
     */
    private $accounts;

    public function __construct()
    {
        $this->username = new ArrayCollection();
        $this->accounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getUsername(): Collection
    {
        return $this->username;
    }

    public function addUsername(Account $username): self
    {
        if (!$this->username->contains($username)) {
            $this->username[] = $username;
            $username->setRoleid($this);
        }

        return $this;
    }

    public function removeUsername(Account $username): self
    {
        if ($this->username->removeElement($username)) {
            // set the owning side to null (unless already changed)
            if ($username->getRoleid() === $this) {
                $username->setRoleid(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setRole($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getRole() === $this) {
                $account->setRole(null);
            }
        }

        return $this;
    }
}
