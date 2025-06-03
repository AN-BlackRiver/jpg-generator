<?php

class Db {
    private static ?Db $instance = null;
    private ?PDO $pdo = null;

    private function __construct() {
        $config = [
            'host' => 'db',
            'dbname' => 'gallery_db',
            'user' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4'
        ];

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['dbname'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {

            error_log("Ошибка подключения к БД: " . $e->getMessage());
            throw new RuntimeException('Ошибка подключения.');
        }
    }

    private function __clone() {}

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    private function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function execute(string $sql, array $params = []): bool
    {
        return $this->query($sql, $params)->rowCount() > 0;
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function close(): void
    {
        $this->pdo = null;
    }
}
