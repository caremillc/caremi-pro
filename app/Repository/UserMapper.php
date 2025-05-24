<?php
namespace App\Repository;

use App\Entity\User;
use Careminate\Databases\Dbal\EntityManager\DataMapper;

class UserMapper
{
    public function __construct(private DataMapper $dataMapper)
    {}

    public function emailExists(string $email): bool
    {
        $query = $this->dataMapper->getConnection()->createQueryBuilder()
            ->select('id')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        return (bool) $query->fetchOne();
    }

    public function save(User $user): void
    {
        if ($this->emailExists($user->getEmail())) {
            throw new \Exception('Email already exists');
        }

        $stmt = $this->dataMapper->getConnection()->prepare("
        INSERT INTO users (username, email, password, created_at)
                   VALUES (:username, :email, :password, :created_at)
    ");

        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':created_at', $user->getCreatedAt()->format('Y-m-d H:i:s'));

        $stmt->executeStatement();

         $id = $this->dataMapper->save($user);
         $user->setId($id);
         $user->setId((int) $this->dataMapper->getConnection()->lastInsertId());
    }

}
