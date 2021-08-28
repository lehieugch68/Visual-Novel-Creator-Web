<?php

namespace App\Entity;

use App\Repository\GameSceneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameSceneRepository::class)
 */
class GameScene
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="gameScenes")
     */
    private $game;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sceneorder;

    /**
     * @ORM\ManyToOne(targetEntity=GameImage::class)
     */
    private $background;

    /**
     * @ORM\ManyToOne(targetEntity=GameSound::class)
     */
    private $music;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isbattle;

    /**
     * @ORM\OneToMany(targetEntity=GameStoryScene::class, mappedBy="gamescene", orphanRemoval=true)
     * @ORM\OrderBy({"contextorder" = "ASC"})
     */
    private $gameStoryScenes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity=GameBattleScene::class, mappedBy="scene", cascade={"persist", "remove"})
     */
    private $gameBattleScene;

    public function __construct()
    {
        $this->gameStoryScenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getSceneorder(): ?int
    {
        return $this->sceneorder;
    }

    public function setSceneorder(?int $sceneorder): self
    {
        $this->sceneorder = $sceneorder;

        return $this;
    }

    public function getBackground(): ?GameImage
    {
        return $this->background;
    }

    public function setBackground(?GameImage $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function getMusic(): ?GameSound
    {
        return $this->music;
    }

    public function setMusic(?GameSound $music): self
    {
        $this->music = $music;

        return $this;
    }

    public function getIsbattle(): ?bool
    {
        return $this->isbattle;
    }

    public function setIsbattle(?bool $isbattle): self
    {
        $this->isbattle = $isbattle;

        return $this;
    }

    /**
     * @return Collection|GameStoryScene[]
     */
    public function getGameStoryScenes(): Collection
    {
        return $this->gameStoryScenes;
    }

    public function addGameStoryScene(GameStoryScene $gameStoryScene): self
    {
        if (!$this->gameStoryScenes->contains($gameStoryScene)) {
            $this->gameStoryScenes[] = $gameStoryScene;
            $gameStoryScene->setGamescene($this);
        }

        return $this;
    }

    public function removeGameStoryScene(GameStoryScene $gameStoryScene): self
    {
        if ($this->gameStoryScenes->removeElement($gameStoryScene)) {
            // set the owning side to null (unless already changed)
            if ($gameStoryScene->getGamescene() === $this) {
                $gameStoryScene->setGamescene(null);
            }
        }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGameBattleScene(): ?GameBattleScene
    {
        return $this->gameBattleScene;
    }

    public function setGameBattleScene(?GameBattleScene $gameBattleScene): self
    {
        // unset the owning side of the relation if necessary
        if ($gameBattleScene === null && $this->gameBattleScene !== null) {
            $this->gameBattleScene->setScene(null);
        }

        // set the owning side of the relation if necessary
        if ($gameBattleScene !== null && $gameBattleScene->getScene() !== $this) {
            $gameBattleScene->setScene($this);
        }

        $this->gameBattleScene = $gameBattleScene;

        return $this;
    }
}
