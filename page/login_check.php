<?php
session_start();
require '../system/pdo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // ค้นหาผู้ใช้
        $stmt = $conn->prepare("SELECT user_id, email, password, user_type FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // ตรวจสอบรหัสผ่าน
            if (password_verify($password, $user['password'])) {
                // เริ่มเซสชัน (Session) สำหรับผู้ใช้
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_type'] = $user['user_type'];

                // นำผู้ใช้ไปยังหน้าที่เหมาะสม
                if ($user['user_type'] === 'customer') {
                    header("Location: customer_dashboard.php");
                } elseif ($user['user_type'] === 'seller') {
                    header("Location: seller_dashboard.php");
                }
                exit();
            } else {
                echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('ไม่พบผู้ใช้'); window.location.href='login.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "'); window.location.href='login.php';</script>";
    }
}


$conn = null;
?>