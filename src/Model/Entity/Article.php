<?php

namespace App\Model\Entity;

use DateTime;


class Article
{
    private ?int $id = null;
    private ?int $userId = null;
    private ?string $title = null;
    private ?string $chapo = null;
    private string|\DateTime $date;
    private \DateTime|string|null $updateDate = null;
    private ?string $content = null;
    private ?string $slug = null;

    // Constructeur de la classe
    public function __construct(array $datas = [])
    {
        $this->date = new \DateTime();
        foreach ($datas as $attr => $value) {
            $method = "set" . ucfirst($attr);
            if (is_callable([$this, $method])) {
                if (method_exists($this, $method) && isset($value)) {
                        $this->$method($value);
                    }
                }
            }
    }

    public function generateSlug(): void
    {

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title . '-' . $this->id)));
        $this->slug = $slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
        $this->generateSlug();
    }

    public function getTitle(): string
    {
        return $this->title;
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
    if(is_string($this->date))
    {
        $this->date = new \DateTime($this->date);
    }

    return $this->date;
}
    //  * @param \DateTime|null $updateDate
    //  */    
    public function setUpdateDate(\DateTime|string|null $updateDate): void
{
    if (is_string($updateDate)) {
        $updateDate = new \DateTime($updateDate);
    }
    $this->updateDate = $updateDate;
}

public function getUpdateDate(): \DateTime|string|null
{
    if(is_string($this->updateDate))
    {
        $this->updateDate = new \DateTime($this->updateDate);
    }
    return $this->updateDate;
}
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setChapo(?string $chapo): void
    {
        $this->chapo = $chapo;
    }

    public function getChapo(): ?string
    {
        return $this->chapo;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
    
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
