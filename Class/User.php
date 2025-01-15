<?php
abstract class User {
    protected $conn;
    protected $table = 'users';
    
    // Propriétés communes
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $password;
    protected $status;
    protected $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Méthodes communes
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                return true;
            }
            return false;
            
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if(password_verify($password, $row['password'])) {
                    // Vérifier le statut pour les enseignants
                    if($row['role'] === 'teacher' && $row['status'] !== 'active') {
                        $errors[] = "Votre compte est en attente d'approbation";
                        return false;  // Retourner false au lieu d'un message d'erreur
                    }
                    
                    // Retourner toutes les informations de l'utilisateur
                    return [
                        "id" => $row['id'],
                        "firstname" => $row['firstname'],
                        "lastname" => $row['lastname'],
                        "email" => $row['email'],
                        "role" => $row['role'],
                        "status" => $row['status']
                    ];
                }
            }
            return false;
            
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Méthode abstraite que chaque classe enfant doit implémenter
    abstract public function getDashboardData();
    abstract public function getPermissions();
    
    // Ajouter cette méthode register
    protected function register($data) {
        $query = "INSERT INTO " . $this->table . " 
                 (firstname, lastname, email, password, role, status) 
                 VALUES 
                 (:firstname, :lastname, :email, :password, :role, :status)";
        
        try {
            // Hash du mot de passe
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt = $this->conn->prepare($query);
            
            // Bind des valeurs
            $stmt->bindParam(':firstname', $data['firstname']);
            $stmt->bindParam(':lastname', $data['lastname']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $data['role']);
            $stmt->bindParam(':status', $data['status']);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
            
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}