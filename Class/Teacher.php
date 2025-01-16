<?php
class Teacher extends User {
    protected $role = 'teacher';
    
    public function register($data) {
        $data['role'] = $this->role;
        $data['status'] = 'pending'; // Les professeurs doivent être validés
        
        return parent::register($data);
    }
    
    public function getDashboardData() {
        // Récupérer les cours créés par le professeur
        $query = "SELECT * FROM courses WHERE teacher_id = :teacher_id";
        // ... implémentation pour la fonction pour la utiliser apres..
    }
    
    public function getPermissions() {
        return [
            'can_create_courses' => true,
            'can_edit_courses' => true,
            'can_view_statistics' => true
        ];
    }
    
    public function getCourseStatistics() {
        // Méthode spécifique aux professeurs
        // ... implémentation ...
    }
} 

