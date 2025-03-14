<?php
session_start(); // เริ่มต้น Session
require '../system/pdo.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบและเป็นผู้ขาย (seller) หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: login.php"); // ไปที่หน้า Login หากไม่ได้เข้าสู่ระบบ
    exit();
}

// ตรวจสอบว่ามีการส่ง Product_id มาหรือไม่
if (!isset($_GET['id'])) {
    header("Location: seller_dashboard.php"); // กลับไปที่หน้า Dashboard หากไม่มี Product_id
    exit();
}

$productId = $_GET['id']; // รับค่า Product_id จาก URL
$sellerId = $_SESSION['user_id']; // รับค่า user_id จาก Session

try {
    // ตรวจสอบการเชื่อมต่อฐานข้อมูล
    if (!$conn) {
        throw new Exception("เชื่อมต่อฐานข้อมูลไม่ได้");
    }

    
    $checkProductSql = "SELECT Sellers_id FROM products WHERE Products_id = :productId AND Sellers_id = (SELECT Sellers_id FROM sellers WHERE user_id = :sellerId)";
    $checkProductStmt = $conn->prepare($checkProductSql);
    $checkProductStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
    $checkProductStmt->bindParam(':sellerId', $sellerId, PDO::PARAM_INT);
    $checkProductStmt->execute();

    if ($checkProductStmt->rowCount() === 0) {
        die("คุณไม่มีสิทธิ์ลบสินค้านี้");
    }

    // ลบสินค้า
    $deleteSql = "DELETE FROM products WHERE Products_id = :productId";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bindParam(':productId', $productId, PDO::PARAM_INT);

    if ($deleteStmt->execute()) {
        header("Location: seller_dashboard.php"); // กลับไปที่หน้า Dashboard หลังลบสินค้า
        exit();
    } else {
        throw new Exception("ไม่สามารถลบสินค้าได้");
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage()); // บันทึกข้อผิดพลาดลง Log
    die("เกิดข้อผิดพลาด: " . $e->getMessage()); // แสดงข้อความผิดพลาดให้ผู้ใช้
}
?>