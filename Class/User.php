<?php
class User {
    private $conn;
    private $table = 'users';
    
    // User properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $role;
    public $status;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Register new user
    public function register($data) {
        $query = "INSERT INTO " . $this->table . "
                (firstname, lastname, email, password, role, status, created_at)
                VALUES
                (:firstname, :lastname, :email, :password, :role, :status, NOW())";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Clean and hash data
            $this->firstname = htmlspecialchars(strip_tags($data['firstname']));
            $this->lastname = htmlspecialchars(strip_tags($data['lastname']));
            $this->email = htmlspecialchars(strip_tags($data['email']));
            $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $this->role = htmlspecialchars(strip_tags($data['role']));
            $this->status = ($this->role === 'teacher') ? 'pending' : 'active';
            
            // Bind data
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':role', $this->role);
            $stmt->bindParam(':status', $this->status);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
            
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Login user
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if(password_verify($password, $row['password'])) {
                    // Check status for teachers
                    if($row['role'] === 'teacher' && $row['status'] !== 'active') {
                        return ["error" => "Your account is pending approval"];
                    }
                    
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
    
    // Check if email exists
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
}