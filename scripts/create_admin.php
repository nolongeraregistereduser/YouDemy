<?php
require_once '../config/database.php';
require_once '../Class/User.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();
    $user = new User($pdo);
    
    // Admin data
    $adminData = [
        'firstname' => 'Admin',
        'lastname' => 'System',
        'email' => 'admin@youdemy.com',
        'password' => 'admin123', // You should change this!
        'role' => 'admin'
    ];
    
    // Check if admin already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminData['email']]);
    
    if ($stmt->rowCount() > 0) {
        echo "Admin user already exists!";
        exit;
    }
    
    // Create admin user
    if ($user->register($adminData)) {
        echo "Admin user created successfully!\n";
        echo "Email: " . $adminData['email'] . "\n";
        echo "Password: " . $adminData['password'] . "\n";
        echo "\nPlease change these credentials after first login!";
    } else {
        echo "Failed to create admin user.";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
} 