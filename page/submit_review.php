<?php
session_start();
require '../system/pdo.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $categories = $_POST['categories'];
    $userId = $_SESSION['user_id'];
    $productId = $_POST['product_id']; // ควรส่ง product_id มาจากฟอร์ม

    try {
        $sql = "INSERT INTO Products_review (Rating_product, Comment_product, Preferred_categories, Cos_id, Products_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$rating, $comment, $categories, $userId, $productId]);
        header("Location: index.php");
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>