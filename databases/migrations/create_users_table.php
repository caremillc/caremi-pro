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
$users = $schema->createTable("users");

$users->addColumn("id", Types::INTEGER, ["autoincrement" => true,]);
$users->addColumn("name", Types::STRING, ["length" => 255]);
$users->addColumn("email", Types::STRING, ["length" => 255]);
$users->addColumn("role", Types::STRING, ["length" => 255,"notnull" => false]);// <-- allow null values
$users->addColumn("password", Types::STRING, ["length" => 255]);
$users->addColumn("created_at", Types::DATETIME_MUTABLE);
$users->addColumn("updated_at", Types::DATETIME_MUTABLE);

$users->setPrimaryKey(["id"]);
$users->addUniqueIndex(["email"]);

// Get platform and generate SQL
$platform = $connection->getDatabasePlatform();
$queries = $schema->toSql($platform); // array of SQL strings

foreach ($queries as $query) {
    echo "Executing: $query\n";
    $connection->executeStatement($query);
}

echo "✅ users table created successfully.\n";
