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

    // ตรวจสอบว่าสินค้านี้เป็นของผู้ขายนี้หรือไม่
    $checkProductSql = "SELECT * FROM products WHERE Products_id = :productId AND Sellers_id = (SELECT Sellers_id FROM sellers WHERE user_id = :sellerId)";
    $checkProductStmt = $conn->prepare($checkProductSql);
    $checkProductStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
    $checkProductStmt->bindParam(':sellerId', $sellerId, PDO::PARAM_INT);
    $checkProductStmt->execute();
    $product = $checkProductStmt->fetch(PDO::FETCH_ASSOC);

    if ($checkProductStmt->rowCount() === 0) {
        die("คุณไม่มีสิทธิ์แก้ไขสินค้านี้");
    }

    // หากมีการส่งข้อมูลแบบ POST (อัปเดตสินค้า)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // รับค่าจากฟอร์ม
        $productFname = $_POST['productFname'] ?? '';
        $productLname = $_POST['productLname'] ?? '';
        $stockQuantity = intval($_POST['stockQuantity'] ?? 0);
        $price = floatval($_POST['price'] ?? 0.00);
        $category = $_POST['category'] ?? '';

        // ตรวจสอบว่ามีข้อมูลครบถ้วนและถูกต้องหรือไม่
        if (empty($productFname) || empty($productLname) || $stockQuantity <= 0 || $price <= 0 || empty($category)) {
            die("กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง");
        }

        // อัปเดตข้อมูลสินค้า
        $updateSql = "UPDATE products SET Product_fname = :fname, Product_lname = :lname, Stock_quantity = :stock_quantity, Product_price = :price, Category = :category WHERE Products_id = :productId";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':fname', $productFname, PDO::PARAM_STR);
        $updateStmt->bindParam(':lname', $productLname, PDO::PARAM_STR);
        $updateStmt->bindParam(':stock_quantity', $stockQuantity, PDO::PARAM_INT);
        $updateStmt->bindParam(':price', $price, PDO::PARAM_STR);
        $updateStmt->bindParam(':category', $category, PDO::PARAM_STR);
        $updateStmt->bindParam(':productId', $productId, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header("Location: seller_dashboard.php"); // กลับไปที่หน้า Dashboard หลังอัปเดตสินค้า
            exit();
        } else {
            throw new Exception("ไม่สามารถอัปเดตสินค้าได้");
        }
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage()); // บันทึกข้อผิดพลาดลง Log
    die("เกิดข้อผิดพลาด: " . $e->getMessage()); // แสดงข้อความผิดพลาดให้ผู้ใช้
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">แก้ไขสินค้า</h1>
        <form method="POST" action="edit_product.php?id=<?= $productId ?>">
            <div class="mb-3">
                <label for="productFname" class="form-label">ชื่อสินค้า</label>
                <input type="text" class="form-control" id="productFname" name="productFname" value="<?= htmlspecialchars($product['Product_fname']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="productLname" class="form-label">นามสกุลสินค้า</label>
                <input type="text" class="form-control" id="productLname" name="productLname" value="<?= htmlspecialchars($product['Product_lname']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="stockQuantity" class="form-label">จำนวนสต็อก</label>
                <input type="number" class="form-control" id="stockQuantity" name="stockQuantity" value="<?= htmlspecialchars($product['Stock_quantity']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">ราคา</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['Product_price']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">หมวดหมู่</label>
                <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($product['Category']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        </form>
    </div>
</body>
</html>