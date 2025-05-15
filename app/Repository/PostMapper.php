<?php
namespace App\Repository;

use App\Entity\Post;
use Doctrine\DBAL\Connection;

class PostMapper
{
    public function __construct(private Connection $connection)
    {
    }

   public function save(Post $post): void
{
    $fields = ['title', 'description', 'image', 'created_at'];
    $params = [':title' => $post->getTitle(),':description' => $post->getDescription(),':image' => $post->getImage(),':created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),];

    if ($post->getUpdatedAt()) {
        $fields[] = 'updated_at';
        $params[':updated_at'] = $post->getUpdatedAt()->format('Y-m-d H:i:s');
    }

    $columns = implode(', ', $fields);
    $placeholders = implode(', ', array_keys($params));

    $sql = "INSERT INTO posts ($columns) VALUES ($placeholders)";
    $stmt = $this->connection->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->executeStatement();
    $post->setId((int) $this->connection->lastInsertId());
}

}
