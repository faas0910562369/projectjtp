<?php
session_start(); // เริ่มต้น Session
require '../system/pdo.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบและเป็นผู้ขาย (seller) หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: login.php"); // ไปที่หน้า Login หากไม่ได้เข้าสู่ระบบ
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์ม POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $productFname = $_POST['productFname'] ?? ''; 
    $productLname = $_POST['productLname'] ?? ''; 
    $stockQuantity = intval($_POST['stockQuantity'] ?? 0); // แปลงเป็นจำนวนเต็ม
    $price = floatval($_POST['price'] ?? 0.00); // แปลงเป็นจำนวนทศนิยม
    $category = $_POST['category'] ?? ''; 
    // ใช้ user_id จาก session
    $sellerId = $_SESSION['user_id'];

    // ตรวจสอบว่ามีข้อมูลครบถ้วนและถูกต้องหรือไม่
    if (empty($productFname) || empty($productLname) || $stockQuantity <= 0 || $price <= 0 || empty($category)) {
        die("กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง");
    }

    try {
        // เชื่อมต่อฐานข้อมูล
        if (!$conn) {
            throw new Exception("เชื่อมต่อฐานข้อมูลไม่ได้");
        }

        // แสดงค่า user_id ที่ใช้ใน session เพื่อตรวจสอบ
        echo "user_id จาก Session: " . $sellerId;

        // ตรวจสอบว่า user_id ที่ใช้มีในฐานข้อมูลหรือไม่
        $checkSellerSql = "SELECT Sellers_id FROM sellers WHERE user_id = :sellerId";
        $checkSellerStmt = $conn->prepare($checkSellerSql);
        $checkSellerStmt->bindParam(':sellerId', $sellerId, PDO::PARAM_INT); // ใช้ PDO::PARAM_INT เนื่องจาก user_id เป็น int
        $checkSellerStmt->execute();
        $row = $checkSellerStmt->fetch(PDO::FETCH_ASSOC);

        // แสดงผลจำนวนแถวที่ได้จากการค้นหา
        echo "จำนวนแถวที่ค้นพบ: " . $checkSellerStmt->rowCount();
        if ($checkSellerStmt->rowCount() === 0) {
            die("ไม่พบผู้ขายนี้ในระบบ, ตรวจสอบค่า user_id ที่ใช้: " . $sellerId);
        }

        // เพิ่มข้อมูลสินค้า
        $sql = "INSERT INTO products (Product_fname, Product_lname, Product_price, Stock_quantity, Category, Sellers_id) 
                VALUES (:fname, :lname, :price, :stock_quantity, :category, :seller_id)";

        // เตรียมคำสั่ง SQL
        $stmt = $conn->prepare($sql);

        // ผูกค่าตัวแปร
        $stmt->bindParam(':fname', $productFname, PDO::PARAM_STR);
        $stmt->bindParam(':lname', $productLname, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR); // ใช้ PDO::PARAM_STR สำหรับทศนิยม
        $stmt->bindParam(':stock_quantity', $stockQuantity, PDO::PARAM_INT);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':seller_id', $row['Sellers_id'], PDO::PARAM_STR); // ใช้ PDO::PARAM_STR เนื่องจาก Sellers_id เป็น char(10)

        // เรียกใช้คำสั่ง execute
        if ($stmt->execute()) {
            header("Location: seller_dashboard.php"); // ไปที่หน้า Dashboard หลังเพิ่มสินค้า
            exit();
        } else {
            throw new Exception("ไม่สามารถเพิ่มสินค้าได้");
        }

    } catch (Exception $e) {
        // บันทึกข้อผิดพลาดและแสดงข้อความ
        error_log("Error: " . $e->getMessage()); // บันทึกข้อผิดพลาดลง Log
        die("เกิดข้อผิดพลาด: " . $e->getMessage()); // แสดงข้อความผิดพลาดให้ผู้ใช้
    }
}
?>