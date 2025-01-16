<?php
class Teacher extends User {
    protected $role = 'teacher';
    
    public function register($data) {
        $data['role'] = $this->role;
        $data['status'] = 'pending';
        return parent::register($data);
    }
    
    public function getDashboardData() {
        try {
            $stats = [];
            
            // Total courses
            $query = "SELECT COUNT(*) as total_courses FROM courses WHERE teacher_id = :teacher_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['teacher_id' => $this->id]);
            $stats['total_courses'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_courses'];
            
            // Total students enrolled
            $query = "SELECT COUNT(DISTINCT user_id) as total_students 
                     FROM course_enrollments ce 
                     JOIN courses c ON ce.course_id = c.id 
                     WHERE c.teacher_id = :teacher_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['teacher_id' => $this->id]);
            $stats['total_students'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_students'];
            
            // Recent courses
            $query = "SELECT * FROM courses 
                     WHERE teacher_id = :teacher_id 
                     ORDER BY created_at DESC LIMIT 5";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['teacher_id' => $this->id]);
            $stats['recent_courses'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Course status counts
            $query = "SELECT status, COUNT(*) as count 
                     FROM courses 
                     WHERE teacher_id = :teacher_id 
                     GROUP BY status";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['teacher_id' => $this->id]);
            $stats['course_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
            
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function getPermissions() {
        return [
            'can_create_courses' => true,
            'can_edit_courses' => true,
            'can_view_statistics' => true
        ];
    }
    
    public function getMyCourses() {
        try {
            $query = "SELECT * FROM courses WHERE teacher_id = :teacher_id ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['teacher_id' => $this->id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function deleteCourse($courseId) {
        try {
            // Vérifier si le cours appartient à ce professeur
            $query = "SELECT id FROM courses WHERE id = :course_id AND teacher_id = :teacher_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'course_id' => $courseId,
                'teacher_id' => $this->id
            ]);

            if ($stmt->rowCount() === 0) {
                return false;
            }

            // Supprimer le cours
            $query = "DELETE FROM courses WHERE id = :course_id AND teacher_id = :teacher_id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                'course_id' => $courseId,
                'teacher_id' => $this->id
            ]);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
} 

