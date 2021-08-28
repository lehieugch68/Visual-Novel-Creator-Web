<?php

namespace App\Entity;

use App\Repository\GameBattleSceneRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameBattleSceneRepository::class)
 */
class GameBattleScene
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $playerhp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $enemyhp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $playeratk;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $enemyatk;

    /**
     * @ORM\ManyToOne(targetEntity=GameImage::class)
     */
    private $playericon;

    /**
     * @ORM\ManyToOne(targetEntity=GameImage::class)
     */
    private $enemyimage;

    /**
     * @ORM\OneToOne(targetEntity=GameScene::class, inversedBy="gameBattleScene", cascade={"persist", "remove"})
     */
    private $scene;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $playerdef;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $enemydef;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $playername;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enemyname;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPlayerhp(): ?int
    {
        return $this->playerhp;
    }

    public function setPlayerhp(?int $playerhp): self
    {
        $this->playerhp = $playerhp;

        return $this;
    }

    public function getEnemyhp(): ?int
    {
        return $this->enemyhp;
    }

    public function setEnemyhp(?int $enemyhp): self
    {
        $this->enemyhp = $enemyhp;

        return $this;
    }

    public function getPlayeratk(): ?int
    {
        return $this->playeratk;
    }

    public function setPlayeratk(?int $playeratk): self
    {
        $this->playeratk = $playeratk;

        return $this;
    }

    public function getEnemyatk(): ?int
    {
        return $this->enemyatk;
    }

    public function setEnemyatk(?int $enemyatk): self
    {
        $this->enemyatk = $enemyatk;

        return $this;
    }

    public function getPlayericon(): ?GameImage
    {
        return $this->playericon;
    }

    public function setPlayericon(?GameImage $playericon): self
    {
        $this->playericon = $playericon;

        return $this;
    }

    public function getEnemyimage(): ?GameImage
    {
        return $this->enemyimage;
    }

    public function setEnemyimage(?GameImage $enemyimage): self
    {
        $this->enemyimage = $enemyimage;

        return $this;
    }

    public function getScene(): ?GameScene
    {
        return $this->scene;
    }

    public function setScene(?GameScene $scene): self
    {
        $this->scene = $scene;

        return $this;
    }

    public function getPlayerdef(): ?int
    {
        return $this->playerdef;
    }

    public function setPlayerdef(?int $playerdef): self
    {
        $this->playerdef = $playerdef;

        return $this;
    }

    public function getEnemydef(): ?int
    {
        return $this->enemydef;
    }

    public function setEnemydef(?int $enemydef): self
    {
        $this->enemydef = $enemydef;

        return $this;
    }

    public function getPlayername(): ?string
    {
        return $this->playername;
    }

    public function setPlayername(?string $playername): self
    {
        $this->playername = $playername;

        return $this;
    }

    public function getEnemyname(): ?string
    {
        return $this->enemyname;
    }

    public function setEnemyname(?string $enemyname): self
    {
        $this->enemyname = $enemyname;

        return $this;
    }
}
