<?php
session_start();
require_once 'database.php';
require_once 'functions.php';
require_once '../models/Reservation.php';
require_once 'ReservationController.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: connexionV2.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: espace_personnel.php");
    exit();
}

$controller = new ReservationController($pdo);
$result = $controller->cancelReservation($_GET['id'], $_SESSION['user_id']);

$_SESSION[$result['success'] ? 'message' : 'error'] = $result['message'];

header("Location: espace_personnel.php");
exit();
?>