<?php 
namespace App\Entity;

use Careminate\Databases\EntityManager\Entity;
 
class Post extends Entity
{
   public function __construct(
        private ?int $id,
        private string $title,
        private string $description,
         private ?string $image = null,
        private \DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        ?int $id = null,
        string $title,
        string $description,
        ?string $image = null,
        ?\DateTimeImmutable $createdAt = null,
    ): Post {
        return new self($id, $title, $description, $image, $createdAt ?? new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}