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
            
            // Total courses (draft + published)
            $query = "SELECT COUNT(*) as total_courses 
                     FROM courses 
                     WHERE teacher_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->id]);
            $stats['total_courses'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_courses'];
            
            // Total students enrolled in teacher's courses
            $query = "SELECT COUNT(DISTINCT e.student_id) as total_students 
                     FROM courses c 
                     LEFT JOIN enrollments e ON c.id = e.course_id 
                     WHERE c.teacher_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->id]);
            $stats['total_students'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_students'];
            
            // Active courses (published only)
            $query = "SELECT COUNT(*) as active_courses 
                     FROM courses 
                     WHERE teacher_id = ? 
                     AND status = 'published'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->id]);
            $stats['active_courses'] = $stmt->fetch(PDO::FETCH_ASSOC)['active_courses'];
            
            return $stats;
            
        } catch(PDOException $e) {
            error_log("Teacher Dashboard Error: " . $e->getMessage());
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
            // Updated query to include tags and student count
            $query = "SELECT c.*, 
                        GROUP_CONCAT(t.name) as tags,
                        COUNT(DISTINCT e.student_id) as student_count
                     FROM courses c
                     LEFT JOIN course_tags ct ON c.id = ct.course_id
                     LEFT JOIN tags t ON ct.tag_id = t.id
                     LEFT JOIN enrollments e ON c.id = e.course_id
                     WHERE c.teacher_id = :teacher_id 
                     GROUP BY c.id
                     ORDER BY c.created_at DESC";
                     
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

    public function getPendingEnrollments() {
        $query = "SELECT e.id as enrollment_id, e.student_id, e.course_id, e.status, 
                         c.title as course_title, u.firstname, u.lastname, u.email
                  FROM enrollments e
                  JOIN courses c ON e.course_id = c.id
                  JOIN users u ON e.student_id = u.id
                  WHERE c.teacher_id = ? AND e.status = 'pending'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveEnrollment($enrollmentId) {
        $query = "UPDATE enrollments 
                  SET status = 'approved' 
                  WHERE id = ? AND 
                        course_id IN (SELECT id FROM courses WHERE teacher_id = ?)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$enrollmentId, $this->id]);
    }

    public function rejectEnrollment($enrollmentId) {
        $query = "UPDATE enrollments 
                  SET status = 'rejected' 
                  WHERE id = ? AND 
                        course_id IN (SELECT id FROM courses WHERE teacher_id = ?)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$enrollmentId, $this->id]);
    }

    public function getPendingEnrollmentsCount() {
        $query = "SELECT COUNT(*) as count
                  FROM enrollments e
                  JOIN courses c ON e.course_id = c.id
                  WHERE c.teacher_id = ? AND e.status = 'pending'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] ?? 0;
    }
} 

