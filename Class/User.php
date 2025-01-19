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
    
    // Ajouter setter pour ID
    public function setId($id) {
        $this->id = $id;
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
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Remove sensitive data
                unset($user['password']);
                return $user;
            }
            return false;
        } catch(PDOException $e) {
            error_log("Login Error in User class: " . $e->getMessage());
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

    public function getId() {
        return $this->id;
    }
}