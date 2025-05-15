<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Careminate\Databases\Dbal\ConnectionFactory;

// Set up base path
define('BASE_PATH', realpath(__DIR__ . '/../../'));

// Load .env
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(BASE_PATH . '/.env');

// Create the connection
$databaseUrl = 'sqlite:///' . BASE_PATH . '/storage/database.sqlite';
$connection = (new ConnectionFactory($databaseUrl))->create();

// Build schema
$schema = new Schema();
$posts = $schema->createTable("posts");

$posts->addColumn("id", Types::INTEGER, ["autoincrement" => true,]);
$posts->addColumn("title", Types::STRING, ["length" => 255]);
$posts->addColumn("description", Types::STRING, ["length" => 255]);
$posts->addColumn("image", Types::BLOB, ["length" => 255,"notnull" => false]);// <-- allow null values
$posts->addColumn("created_at", Types::DATETIME_MUTABLE);
$posts->addColumn("updated_at", Types::DATETIME_MUTABLE,["notnull" => false]);

$posts->setPrimaryKey(["id"]);
$posts->addUniqueIndex(["title"]);

// Get platform and generate SQL
$platform = $connection->getDatabasePlatform();
$queries = $schema->toSql($platform); // array of SQL strings

foreach ($queries as $query) {
    echo "Executing: $query\n";
    $connection->executeStatement($query);
}

echo "✅ posts table created successfully.\n";
