<?php
session_start();
require_once 'config/database.php';
require_once 'Class/Enrollment.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: auth.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    $enrollmentObj = new Enrollment($db);
    $studentId = $_SESSION['user']['id'];
    $courseId = (int)$_POST['course_id'];
    
    if ($enrollmentObj->requestEnrollment($studentId, $courseId)) {
        $_SESSION['success'] = "Votre demande d'inscription a été envoyée avec succès.";
    } else {
        $_SESSION['error'] = "Une erreur s'est produite ou vous avez déjà demandé l'inscription.";
    }
    
    header("Location: course-details.php?id=" . $courseId);
    exit();
} else {
    header('Location: index.php');
    exit();
} 