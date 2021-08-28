<?php

namespace App\Entity;

use App\Repository\GameStorySceneRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameStorySceneRepository::class)
 */
class GameStoryScene
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=GameScene::class, inversedBy="gameStoryScenes")
     */
    private $gamescene;

    /**
     * @ORM\ManyToOne(targetEntity=GameImage::class)
     */
    private $talkericon;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $contextorder;

    /**
     * @ORM\ManyToOne(targetEntity=GameImage::class)
     */
    private $characterimage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $talker;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamescene(): ?GameScene
    {
        return $this->gamescene;
    }

    public function setGamescene(?GameScene $gamescene): self
    {
        $this->gamescene = $gamescene;

        return $this;
    }

    public function getTalkericon(): ?GameImage
    {
        return $this->talkericon;
    }

    public function setTalkericon(?GameImage $talkericon): self
    {
        $this->talkericon = $talkericon;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getContextorder(): ?int
    {
        return $this->contextorder;
    }

    public function setContextorder(?int $contextorder): self
    {
        $this->contextorder = $contextorder;

        return $this;
    }

    public function getCharacterimage(): ?GameImage
    {
        return $this->characterimage;
    }

    public function setCharacterimage(?GameImage $characterimage): self
    {
        $this->characterimage = $characterimage;

        return $this;
    }

    public function getTalker(): ?string
    {
        return $this->talker;
    }

    public function setTalker(?string $talker): self
    {
        $this->talker = $talker;

        return $this;
    }
}
