<?php

class Database {
    private $pdo;

    public function __construct($path) {
        try {
            $this->pdo = new PDO("sqlite:" . $path);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка базы данных: " . $e->getMessage());
        }
    }

    public function Execute($sql) {
        return $this->pdo->exec($sql);
    }

    public function Fetch($sql) {
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function Create($table, $data) {
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        
        $stmt = $this->pdo->prepare("INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function Read($table, $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function Update($table, $id, $data) {
        $sets = [];
        foreach ($data as $key => $value) {
            $sets[] = "{$key} = :{$key}";
        }
        $sql = "UPDATE {$table} SET " . implode(", ", $sets) . " WHERE id = :target_id";
        
        $data['target_id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function Delete($table, $id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function Count($table) {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM {$table}");
        return (int)$stmt->fetchColumn();
    }
}