<?php
session_start();
require_once '../config/database.php';
require_once __DIR__ . '/../controllers/AdminController.php';

// Vérification de l'authentification et du rôle admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /login.php');
    exit();
}

$adminController = new AdminController($db);
$dashboardData = $adminController->getDashboardData();

header('Content-Type: application/json');
echo json_encode($dashboardData);