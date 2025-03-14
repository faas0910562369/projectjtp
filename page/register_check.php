<?php
session_start();
require '../system/pdo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $user_type = $_POST['user_type']; 

    try {
        // ตรวจสอบว่าอีเมลซ้ำหรือไม่
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('อีเมลนี้ถูกใช้แล้ว');</script>";
            exit();
        }

        // เพิ่มข้อมูลในตาราง users
        $stmt = $conn->prepare("INSERT INTO users (email, password, user_type) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $user_type]);

        // ดึง user_id ที่เพิ่งสร้าง
        $user_id = $conn->lastInsertId();

        
        if ($user_type == 'customer') {
            $cos_id = uniqid('C'); // สร้างรหัสลูกค้า
            $stmt = $conn->prepare("INSERT INTO customers (cos_id, fname, lname, phone, address, user_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$cos_id, $fname, $lname, $phone, $address, $user_id]);
        } elseif ($user_type == 'seller') {
            $seller_id = uniqid('S'); // สร้างรหัสผู้ขาย
            $stmt = $conn->prepare("INSERT INTO sellers (Sellers_id, Sellers_fname, Sellers_lname, Sellers_email, Sellers_phone, Sellers_address, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$seller_id, $fname, $lname, $email, $phone, $address, $user_id])) {
                echo "<script>alert('บันทึกข้อมูลผู้ขายสำเร็จ');</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูลผู้ขาย');</script>";
            }
        }

        
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_type'] = $user_type;

        echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location.href='login.php';</script>";
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "');</script>";
    }
}

// ปิดการเชื่อมต่อ
$conn = null;
?>