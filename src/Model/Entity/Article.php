<?php

namespace App\Model\Entity;

use DateTime;
use App\Model\Repository\UserRepository;

class Article
{
    private ?int $id = null;
    private ?int $userId = null;
    private ?string $title = null;
    private ?string $chapo = null;
    private string|\DateTime $date;
    private ?\DateTime $updateDate = null;
    private ?string $content = null;
    private ?string $slug = null;

    // Constructeur de la classe
    public function __construct(array $datas = [])
    {
        $this->date = new \DateTime();
        $this->updateDate = new \DateTime();
        foreach ($datas as $attr => $value) {
            $method = "set" . ucfirst($attr);
            if (is_callable([$this, $method])) {
                if (method_exists($this, $method) && isset($value)) {

                    if ($attr === 'updateDate' && is_string($value) && !empty($value)) {
                        $value = new \DateTime($value);
                    }
                    $this->$method($value);
                }
            }
        }
    }

    // j'ai mis dans la méthode slug le title et l'id, est ce la bonne pratique? 
    public function generateSlug(): void
    {

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title . '-' . $this->id)));
        $this->slug = $slug;
    }

    // Méthodes à mettre en place (get, set etc..) de connexion
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
        return $this->date;
    }
    //  * @param \DateTime|null $updateDate
    //  */    
    public function setUpdateDate(?\DateTime $updateDate): void
    {
        $this->updateDate = $updateDate;
    }


    public function getUpdateDate(): ?\DateTime
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
    public function getAuthorFullName(): ?string
    {
        // Vérifiez si l'ID de l'utilisateur est défini
        if ($this->userId) {
            // Utilisez une méthode pour récupérer le nom complet de l'utilisateur en fonction de son ID
            $userRepository = new UserRepository();
            $user = $userRepository->getUserBy('id', $this->userId);

            // Vérifiez si l'utilisateur a été trouvé
            if ($user) {
                return $user->getFullName();
            }
        }
        return null;
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
