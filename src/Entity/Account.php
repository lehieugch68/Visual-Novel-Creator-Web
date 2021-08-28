<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=AccountRole::class, inversedBy="accounts")
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="creator")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity=GameImage::class, mappedBy="uploader")
     */
    private $gameImages;

    /**
     * @ORM\OneToMany(targetEntity=GameSound::class, mappedBy="uploader")
     */
    private $gameSounds;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->gameImages = new ArrayCollection();
        $this->gameSounds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?AccountRole
    {
        return $this->role;
    }

    public function setRole(?AccountRole $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setCreator($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getCreator() === $this) {
                $game->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameImage[]
     */
    public function getGameImages(): Collection
    {
        return $this->gameImages;
    }

    public function addGameImage(GameImage $gameImage): self
    {
        if (!$this->gameImages->contains($gameImage)) {
            $this->gameImages[] = $gameImage;
            $gameImage->setUploader($this);
        }

        return $this;
    }

    public function removeGameImage(GameImage $gameImage): self
    {
        if ($this->gameImages->removeElement($gameImage)) {
            // set the owning side to null (unless already changed)
            if ($gameImage->getUploader() === $this) {
                $gameImage->setUploader(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameSound[]
     */
    public function getGameSounds(): Collection
    {
        return $this->gameSounds;
    }

    public function addGameSound(GameSound $gameSound): self
    {
        if (!$this->gameSounds->contains($gameSound)) {
            $this->gameSounds[] = $gameSound;
            $gameSound->setUploader($this);
        }

        return $this;
    }

    public function removeGameSound(GameSound $gameSound): self
    {
        if ($this->gameSounds->removeElement($gameSound)) {
            // set the owning side to null (unless already changed)
            if ($gameSound->getUploader() === $this) {
                $gameSound->setUploader(null);
            }
        }

        return $this;
    }
}
