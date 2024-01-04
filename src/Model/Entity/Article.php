<?php
namespace App\Model\Entity;

class Article {
    private ?int $id = null;
    private ?string $title = null;
    private \DateTime $date;
    private \DateTime $updateDate;
    private ?string $content = null;
    private ?string $chapo = null;
    private ?int $userId = null;
    private ?string $slug = null;
    //je veux integrer le nom du userId 13/10 question thibault

    // Constructeur de la classe
    public function __construct(array $datas=[])
    {
        $this->date = new \DateTime();
        foreach($datas as $attr=>$value){
            $method="set".ucfirst($attr);
            if(is_callable([$this,$method])){
                $this->$method($value);
            }
        }
    }

    // Méthodes à mettre en place (get, set etc..) de connexion
    public function getId(): ?int {
        return $this->id;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    } 
    
    public function setDate(\DateTime $date): void
    {
        $this->date = $date; 
    }
    public function getDate(): \DateTime
    {
        return $this->date;
    } 
    
    public function setTitle(string $title): void
    {
        $this->title = $title; 
    }
    public function setUpdateDate(\DateTime $updateDate): void
    {
        $this->updateDate = $updateDate; 
    }
    public function getUpdateDate(): \DateTime 
    {
        return $this->updateDate; 
    }
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setChapo(string $chapo): void
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
}
