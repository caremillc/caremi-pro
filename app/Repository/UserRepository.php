<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Careminate\Authentication\Contracts\AuthRepositoryInterface;
use Doctrine\DBAL\Connection;

class UserRepository implements AuthRepositoryInterface
{
    public function __construct(private Connection $connection) {}

    public function findById(int|string $id): ?User
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('id', 'username', 'email', 'password') // Make sure password is selected
            ->from('users')
            ->where('id = :id')
            ->setParameter('id', $id);

        $result = $queryBuilder->executeQuery();
        $row = $result->fetchAssociative();

        if (!$row) {
            return null;
        }

        // Using setter pattern (more consistent with your findByEmail)
        $user = new User();
        $user->setId((int) $row['id']);
        $user->setUsername($row['username']);
        $user->setEmail($row['email']);
        $user->setPassword($row['password']);

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        $data = $query->fetchAssociative();

        if (! $data) {
            return null;
        }

        $user = new User();
        $user->setId((int) $data['id']);
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        return $user;
    }
}
