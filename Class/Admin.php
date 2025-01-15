<?php
class Admin extends User {
    protected $role = 'admin';
    
    public function register($data) {
        $data['role'] = $this->role;
        $data['status'] = 'active';
        
        return parent::register($data);
    }
    
    public function getDashboardData() {
        // Récupérer les statistiques globales
        $stats = [];
        
        // Nombre total d'utilisateurs
        $query = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->conn->query($query);
        $stats['total_users'] = $stmt->fetchColumn();
        
        // Autres statistiques spécifiques à l'admin
        return $stats;
    }
    
    public function getPermissions() {
        return [
            'can_manage_users' => true,
            'can_manage_courses' => true,
            'can_view_all_statistics' => true,
            'can_manage_categories' => true,
            'can_validate_teachers' => true
        ];
    }
} 