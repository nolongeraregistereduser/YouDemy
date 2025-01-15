<?php
class Tag {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM tags ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tags (name) VALUES (?)");
            return $stmt->execute([$name]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Code pour duplicate entry
                return false;
            }
            throw $e;
        }
    }

    public function update($id, $name) {
        $stmt = $this->pdo->prepare("UPDATE tags SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tags WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function bulkCreate($tags) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT IGNORE INTO tags (name) VALUES (?)");
            $success = true;
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $success = $success && $stmt->execute([$tag]);
                }
            }
            if ($success) {
                $this->pdo->commit();
                return true;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
} 