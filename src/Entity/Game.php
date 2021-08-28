<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="games")
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createdat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=GameImage::class)
     */
    private $imagecover;

    /**
     * @ORM\OneToMany(targetEntity=GameIntro::class, mappedBy="game")
     * @ORM\OrderBy({"introorder" = "ASC"})
     */
    private $gameIntros;

    /**
     * @ORM\OneToMany(targetEntity=GameScene::class, mappedBy="game")
     * @ORM\OrderBy({"sceneorder" = "ASC"})
     */
    private $gameScenes;

    public function __construct()
    {
        $this->gameIntros = new ArrayCollection();
        $this->gameScenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): ?Account
    {
        return $this->creator;
    }

    public function setCreator(?Account $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedat(): ?string
    {
        return $this->createdat;
    }

    public function setCreatedat(?string $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImagecover(): ?GameImage
    {
        return $this->imagecover;
    }

    public function setImagecover(?GameImage $imagecover): self
    {
        $this->imagecover = $imagecover;

        return $this;
    }

    /**
     * @return Collection|GameIntro[]
     */
    public function getGameIntros(): Collection
    {
        return $this->gameIntros;
    }

    public function addGameIntro(GameIntro $gameIntro): self
    {
        if (!$this->gameIntros->contains($gameIntro)) {
            $this->gameIntros[] = $gameIntro;
            $gameIntro->setGame($this);
        }

        return $this;
    }

    public function removeGameIntro(GameIntro $gameIntro): self
    {
        if ($this->gameIntros->removeElement($gameIntro)) {
            // set the owning side to null (unless already changed)
            if ($gameIntro->getGame() === $this) {
                $gameIntro->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameScene[]
     */
    public function getGameScenes(): Collection
    {
        return $this->gameScenes;
    }

    public function addGameScene(GameScene $gameScene): self
    {
        if (!$this->gameScenes->contains($gameScene)) {
            $this->gameScenes[] = $gameScene;
            $gameScene->setGame($this);
        }

        return $this;
    }

    public function removeGameScene(GameScene $gameScene): self
    {
        if ($this->gameScenes->removeElement($gameScene)) {
            // set the owning side to null (unless already changed)
            if ($gameScene->getGame() === $this) {
                $gameScene->setGame(null);
            }
        }

        return $this;
    }
}
