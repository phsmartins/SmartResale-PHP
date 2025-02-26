<?php

require __DIR__ . "/../vendor/autoload.php";

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

$environment = getenv("APP_ENV") ?: "development";
$dotenv = Dotenv::createImmutable(__DIR__ . "/..", ".env.{$environment}");
$dotenv->load();

$builder = new ContainerBuilder();

$builder->addDefinitions([
    PDO::class => function (): PDO {
        $dbPath = __DIR__ . $_ENV['DB_PATH'];

        try {
            $pdo = new PDO("sqlite:{$dbPath}");
            $pdo->exec("PRAGMA foreign_keys = ON;");

            return $pdo;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Erro ao se conectar no banco de dados: "
                . $e->getMessage()
            );
        }
    },
    Engine::class => function () {
        $templatePath = __DIR__ . '/../view';
        return new Engine($templatePath);
    }
]);

/** @var ContainerInterface $container */
try {
    $container = $builder->build();
} catch (Exception $e) {
    error_log($e->getMessage());
}

return $container;
