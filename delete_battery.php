<?php
session_start();
include "db.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM batteries WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "success:Battery deleted successfully!";
    } else {
        $_SESSION['message'] = "error:Error deleting battery: " . $conn->error;
    }
}

header("Location: Dashboard.php");
exit();
?>