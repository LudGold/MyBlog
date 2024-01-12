<?php

namespace App\Model\Entity;

class Comment
{
    private ?int $id = null;
    private \DateTime $date;
    private \DateTime $dateUpdate;
    private ?int $userId = null; // mettre le lastname du userid
    private ?string $content = null;

    public function __construct()
    {
        $this->date = new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setDate_update(\DateTime $dateUpdate): void
    {
        $this->dateUpdate = $dateUpdate;
    }

    public function getDate_update(): \DateTime
    {
        return $this->dateUpdate;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
