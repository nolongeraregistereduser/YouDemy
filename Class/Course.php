<?php
class Course {
    private $pdo;
    private $table = 'courses';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                 (title, description, content, image_url, video_url, teacher_id, category_id, status) 
                 VALUES 
                 (:title, :description, :content, :image_url, :video_url, :teacher_id, :category_id, :status)";
        
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':content' => $data['content'],
                ':image_url' => $data['image_url'],
                ':video_url' => $data['video_url'],
                ':teacher_id' => $data['teacher_id'],
                ':category_id' => $data['category_id'],
                ':status' => 'draft'
            ]);

            $courseId = $this->pdo->lastInsertId();

            // Ajouter les tags si présents
            if (!empty($data['tags'])) {
                $this->addCourseTags($courseId, $data['tags']);
            }

            $this->pdo->commit();
            return true;

        } catch(PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function addCourseTags($courseId, $tagIds) {
        $query = "INSERT INTO course_tags (course_id, tag_id) VALUES (:course_id, :tag_id)";
        $stmt = $this->pdo->prepare($query);

        foreach ($tagIds as $tagId) {
            $stmt->execute([
                ':course_id' => $courseId,
                ':tag_id' => $tagId
            ]);
        }
    }

    public function getByTeacherId($teacherId) {
        $query = "SELECT c.*, GROUP_CONCAT(t.name) as tags 
                 FROM " . $this->table . " c 
                 LEFT JOIN course_tags ct ON c.id = ct.course_id 
                 LEFT JOIN tags t ON ct.tag_id = t.id 
                 WHERE c.teacher_id = :teacher_id 
                 GROUP BY c.id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':teacher_id' => $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                 SET title = :title, 
                     description = :description, 
                     content = :content, 
                     image_url = :image_url, 
                     video_url = :video_url, 
                     category_id = :category_id, 
                     status = :status 
                 WHERE id = :id AND teacher_id = :teacher_id";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':content' => $data['content'],
                ':image_url' => $data['image_url'],
                ':video_url' => $data['video_url'],
                ':category_id' => $data['category_id'],
                ':status' => $data['status'],
                ':teacher_id' => $data['teacher_id']
            ]);

            // Mettre à jour les tags
            if (isset($data['tags'])) {
                // Supprimer les anciens tags
                $this->deleteCourseTags($id);
                // Ajouter les nouveaux tags
                $this->addCourseTags($id, $data['tags']);
            }

            $this->pdo->commit();
            return true;

        } catch(PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function deleteCourseTags($courseId) {
        $query = "DELETE FROM course_tags WHERE course_id = :course_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':course_id' => $courseId]);
    }
} 