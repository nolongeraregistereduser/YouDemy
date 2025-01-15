<?php
require_once '../config/database.php';
require_once '../Class/User.php';
require_once '../Class/Admin.php';

$db = new Database();
$pdo = $db->getConnection();

// Créer une instance de Admin
$admin = new Admin($pdo);

$adminData = [
    'firstname' => 'Admin',
    'lastname' => 'System',
    'email' => 'admin@youdemy.com',
    'password' => 'admin123'
];

if ($admin->register($adminData)) {
    echo "Admin account created successfully!";
} else {
    echo "Error creating admin account.";
} 