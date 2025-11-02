<?php

// abstract = ne peux être instancié  pas de new AbstractModel() elle peut être simplement extends
abstract class AbstractModel implements OrmInterface
{
    // methode qui peut être override (modifiée par la classe enfant)
    protected string $table = '';

    public PDO|null $pdo = null;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM" . $this->table . " WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table. " WHERE id = ?");

        return $stmt->execute([$id]);
    }
}