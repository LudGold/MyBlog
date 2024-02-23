<?php

namespace App\Model\Entity;

use DateTime;


class Comment
{
    private ?int $id = null;
    private string|\DateTime $date;
    private ?string $pseudo = null;
    private ?string $content = null;
    private ?int $articleId = null;
    private ?int $userId = null; // mettre le lastname du userid methode getauthorfullname
    private ?string $mail = null;
    private ?bool $isPending;
    private ?bool $isApproved;
    private ?bool $isRejected;
    private ?string $status = null;



    public function __construct(array $datas = [])
    {
        $this->date = new \DateTime();
        $this->isPending = false;
        $this->isApproved = false;
        $this->isRejected = false;
        foreach ($datas as $attr => $value) {
            $method = "set" . ucfirst($attr);
            if (is_callable([$this, $method])) {
                if (method_exists($this, $method) && isset($value)) {
                    $this->$method($value);
                }
            }
        }
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setDate($date): void
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        $this->date = $date;
    }
    public function getDate(): string|\DateTime
    {
        if (is_string($this->date)) {
            $this->date = new \DateTime($this->date);
        }

        return $this->date;
    }
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    public function getArticleId(): ?int
    {
        return $this->articleId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function getmail(): ?string
    {
        return $this->mail;
    }
    public function getIsPending(): ?bool
    {
        return $this->isPending;
    }

    public function setIsPending(bool $isPending): self
    {
        $this->isPending = $isPending;

        return $this;
    }
    public function getIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }
    public function getIsRejected(): ?bool
    {
        return $this->isRejected;
    }

    public function setIsRejected(bool $isRejected): self
    {
        $this->isRejected = $isRejected;

        return $this;
    }
    public function getStatus(): ?string
    {
        if ($this->isPending) {
            return 'En attente';
        } elseif ($this->isApproved) {
            return 'Approuvé';
        } elseif ($this->isRejected) {
            return 'Rejeté';
        } else {
            return null;
        }
    }
}
