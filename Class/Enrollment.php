<?php
class Enrollment {
    private $conn;
    private $table = 'enrollments';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function isEnrolled($studentId, $courseId) {
        $query = "SELECT status FROM " . $this->table . 
                " WHERE student_id = ? AND course_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$studentId, $courseId]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['status'] === 'approved';
    }

    public function requestEnrollment($studentId, $courseId) {
        // Check if enrollment already exists
        $query = "SELECT id, status FROM " . $this->table . 
                " WHERE student_id = ? AND course_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$studentId, $courseId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            if ($existing['status'] === 'rejected') {
                // If previously rejected, allow new request
                $query = "UPDATE " . $this->table . 
                        " SET status = 'pending' 
                          WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$existing['id']]);
            }
            return false; // Already enrolled or pending
        }

        // Create new enrollment request
        $query = "INSERT INTO " . $this->table . 
                " (student_id, course_id, status) 
                  VALUES (?, ?, 'pending')";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$studentId, $courseId]);
    }

    public function getEnrollmentStatus($studentId, $courseId) {
        $query = "SELECT status FROM " . $this->table . 
                " WHERE student_id = ? AND course_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$studentId, $courseId]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['status'] : null;
    }
} 