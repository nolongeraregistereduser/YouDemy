<?php
class Course {
    private $pdo;
    private $table = 'courses';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                 (title, content, content_type, content_url, image_url, video_url, teacher_id, category_id, status) 
                 VALUES 
                 (:title, :content, :content_type, :content_url, :image_url, :video_url, :teacher_id, :category_id, :status)";
        
        try {
            $this->pdo->beginTransaction();
            
            // Debug
            error_log("Query: " . $query);
            error_log("Data: " . print_r($data, true));

            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute([
                ':title' => $data['title'],
                ':content' => $data['content'],
                ':content_type' => $data['content_type'],
                ':content_url' => $data['content_url'],
                ':image_url' => $data['image_url'],
                ':video_url' => $data['video_url'],
                ':teacher_id' => $data['teacher_id'],
                ':category_id' => $data['category_id'],
                ':status' => 'published'
            ]);

            if (!$result) {
                error_log("Execute error: " . print_r($stmt->errorInfo(), true));
            }

            $courseId = $this->pdo->lastInsertId();

            if (!empty($data['tags'])) {
                $this->addCourseTags($courseId, $data['tags']);
            }

            $this->pdo->commit();
            return true;

        } catch(PDOException $e) {
            $this->pdo->rollBack();
            error_log("Create Course Error: " . $e->getMessage());
            throw $e;
        }
    }

    private function addCourseTags($courseId, $tags) {
        $query = "INSERT INTO course_tags (course_id, tag_id) VALUES (:course_id, :tag_id)";
        $stmt = $this->pdo->prepare($query);

        foreach ($tags as $tagId) {
            $stmt->execute([
                ':course_id' => $courseId,
                ':tag_id' => $tagId
            ]);
        }
    }

    public function getByTeacherId($teacherId) {
        $query = "SELECT 
                    c.*,
                    GROUP_CONCAT(DISTINCT t.name) as tags,
                    COUNT(DISTINCT e.student_id) as student_count
                 FROM " . $this->table . " c 
                 LEFT JOIN course_tags ct ON c.id = ct.course_id 
                 LEFT JOIN tags t ON ct.tag_id = t.id 
                 LEFT JOIN enrollments e ON c.id = e.course_id
                 WHERE c.teacher_id = :teacher_id 
                 GROUP BY c.id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':teacher_id' => $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($courseId, $data) {
        $query = "UPDATE " . $this->table . " 
                 SET title = :title,
                     description = :description,
                     content = :content,
                     content_type = :content_type,
                     content_url = :content_url,
                     image_url = CASE WHEN :image_url IS NOT NULL THEN :image_url ELSE image_url END,
                     video_url = :video_url,
                     category_id = :category_id,
                     status = :status
                 WHERE id = :id AND teacher_id = :teacher_id";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':id' => $courseId,
                ':title' => $data['title'],
                ':description' => '', // Empty since we're not using it
                ':content' => $data['content'],
                ':content_type' => $data['content_type'],
                ':content_url' => $data['content_url'] ?? null,
                ':image_url' => $data['image_url'] ?? null,
                ':video_url' => $data['video_url'] ?? null,
                ':category_id' => $data['category_id'],
                ':status' => $data['status'],
                ':teacher_id' => $data['teacher_id']
            ]);

            // Update tags
            if (isset($data['tags'])) {
                // Remove old tags
                $query = "DELETE FROM course_tags WHERE course_id = ?";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$courseId]);

                // Add new tags
                $this->addCourseTags($courseId, $data['tags']);
            }

            $this->pdo->commit();
            return true;

        } catch(PDOException $e) {
            $this->pdo->rollBack();
            error_log("Update Course Error: " . $e->getMessage());
            return false;
        }
    }

    public function getById($id) {
        $query = "SELECT c.*, GROUP_CONCAT(t.id) as tag_ids 
                  FROM " . $this->table . " c
                  LEFT JOIN course_tags ct ON c.id = ct.course_id
                  LEFT JOIN tags t ON ct.tag_id = t.id
                  WHERE c.id = :id
                  GROUP BY c.id";
                  
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllPublished($categoryId = null, $searchQuery = '') {
        $query = "SELECT c.*, 
                         u.firstname as teacher_firstname, 
                         u.lastname as teacher_lastname,
                         CONCAT(u.firstname, ' ', u.lastname) as teacher_name,
                         COUNT(DISTINCT e.student_id) as enrollment_count,
                         GROUP_CONCAT(DISTINCT t.name) as tags,
                         CASE 
                            WHEN c.image_url LIKE 'http%' THEN c.image_url 
                            ELSE CONCAT('/Youdemy/', c.image_url)
                         END as image_url
                  FROM courses c
                  LEFT JOIN users u ON c.teacher_id = u.id
                  LEFT JOIN enrollments e ON c.id = e.course_id
                  LEFT JOIN course_tags ct ON c.id = ct.course_id
                  LEFT JOIN tags t ON ct.tag_id = t.id
                  WHERE c.status = 'published'";
        
        $params = [];
        
        if (!is_null($categoryId) && $categoryId > 0) {
            $query .= " AND c.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }
        
        if (!empty($searchQuery)) {
            $query .= " AND (c.title LIKE :search 
                            OR c.description LIKE :search 
                            OR CONCAT(u.firstname, ' ', u.lastname) LIKE :search
                            OR t.name LIKE :search)";
            $params[':search'] = "%$searchQuery%";
        }
        
        $query .= " GROUP BY c.id ORDER BY enrollment_count DESC";
        
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error or handle it appropriately
            error_log("Error in getAllPublished: " . $e->getMessage());
            return [];
        }
    }

    public function getPreviewContent($courseId) {
        // Get limited preview content for non-enrolled students
        $query = "SELECT c.title, c.description, 
                  c.content_type,
                  c.content_url,
                  c.image_url,
                  c.video_url,
                  COUNT(DISTINCT e.student_id) as total_students,
                  u.firstname as teacher_firstname, u.lastname as teacher_lastname,
                  cat.name as category_name,
                  c.status
                  FROM courses c
                  LEFT JOIN users u ON c.teacher_id = u.id
                  LEFT JOIN categories cat ON c.category_id = cat.id
                  LEFT JOIN enrollments e ON c.id = e.course_id
                  WHERE c.id = ? AND c.status = 'published'
                  GROUP BY c.id";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$courseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCourseDetails($courseId) {
        $query = "SELECT c.*, u.firstname as teacher_firstname, 
                 u.lastname as teacher_lastname, 
                 cat.name as category_name,
                 COUNT(DISTINCT e.student_id) as total_students
                 FROM courses c 
                 LEFT JOIN users u ON c.teacher_id = u.id
                 LEFT JOIN categories cat ON c.category_id = cat.id
                 LEFT JOIN enrollments e ON c.id = e.course_id
                 WHERE c.id = ? AND c.status = 'published'
                 GROUP BY c.id";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$courseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isStudentEnrolled($courseId, $studentId) {
        $query = "SELECT COUNT(*) FROM enrollments 
                  WHERE course_id = ? AND student_id = ?";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$courseId, $studentId]);
        return $stmt->fetchColumn() > 0;
    }
} 