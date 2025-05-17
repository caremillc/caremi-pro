<?php declare(strict_types=1);
namespace App\Entity;

use Careminate\Databases\EntityManager\Entity;
use Careminate\Authentication\Contracts\AuthUserInterface;
use DateTimeImmutable;

class User extends Entity implements AuthUserInterface
{
    protected ?int $id = null;
    protected string $username;
    protected string $email;
    protected string $password;
    protected ?\DateTimeImmutable $createdAt = null;
    protected ?\DateTimeImmutable $updatedAt = null;
     

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public static function create(string $username, string $email, string $plainPassword): self
    {
        return new self([
            'username'  => $username,
            'email'     => $email,
            'password'  => password_hash($plainPassword, PASSWORD_DEFAULT),
            'createdAt' => new \DateTimeImmutable()
        ]);
    }

    public function getAuthId(): int|string
    {
        return $this->id ?? throw new \UnexpectedValueException('Auth ID cannot be null');
    }

    public function getTableName(): string
    {
        return 'users';
    }
}
