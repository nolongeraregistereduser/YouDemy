<?php
class Student extends User {
    protected $role = 'student';
    
    public function register($data) {
        $data['role'] = $this->role;
        $data['status'] = 'active'; // Les étudiants sont toujours actifs par défaut
        
        return parent::register($data);
    }
    
    public function getDashboardData() {
        // Récupérer les cours auxquels l'étudiant est inscrit
        $query = "SELECT c.* FROM courses c 
                 JOIN course_enrollments ce ON c.id = ce.course_id 
                 WHERE ce.student_id = :student_id";
        // ... implémentation dial had function apres pOUR TRAVAILLER AVEC LE CONCEPT DE POLYMORPHISME...
    }
    
    public function getPermissions() {
        return [
            'can_enroll_courses' => true,
            'can_view_courses' => true,
            'can_submit_assignments' => true
        ];
    }
} 