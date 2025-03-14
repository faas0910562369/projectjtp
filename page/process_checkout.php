<?php
session_start();
require '../system/pdo.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$customerId = $_SESSION['user_id']; // รหัสลูกค้าจาก session
$shippingAddress = $_POST['shippingAddress']; // ที่อยู่จัดส่งจากฟอร์ม
$paymentMethod = $_POST['paymentMethod']; // วิธีการชำระเงินจากฟอร์ม
$productId = $_POST['productId']; // รหัสสินค้าจากฟอร์ม
$quantity = $_POST['quantity']; // จำนวนสินค้าจากฟอร์ม

try {
    // ดึงข้อมูลสินค้า
    $sql = "SELECT * FROM products WHERE Products_id = :productId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception("ไม่พบสินค้า");
    }

    // คำนวณยอดรวมย่อย
    $subtotal = $product['Product_price'] * $quantity;

    // บันทึกข้อมูลคำสั่งซื้อลงในตาราง orders
    $sql = "INSERT INTO orders (Orders_date, Total_price, Orders_address, Cos_id, `Status`) 
            VALUES (NOW(), :totalPrice, :shippingAddress, :customerId, 'กำลังดำเนินการ')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':totalPrice', $subtotal);
    $stmt->bindParam(':shippingAddress', $shippingAddress);
    $stmt->bindParam(':customerId', $customerId);
    $stmt->execute();

    // ดึง Orders_id ที่เพิ่งสร้าง
    $orderId = $conn->lastInsertId();

    // บันทึกข้อมูลการจัดส่งลงในตาราง shipments
    $trackingNumber = rand(1000000000, 9999999999); // สร้างหมายเลขติดตามแบบสุ่ม
    $sql = "INSERT INTO shipments (Tracking_number, Shipments_date, Deliver_status, Cos_id, Orders_id, Status_id) 
            VALUES (:trackingNumber, NOW(), 'เตรียมจัดส่ง', :customerId, :orderId, 'ST001')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':trackingNumber', $trackingNumber);
    $stmt->bindParam(':customerId', $customerId);
    $stmt->bindParam(':orderId', $orderId);
    $stmt->execute();

    // บันทึกข้อมูลรายละเอียดคำสั่งซื้อลงในตาราง orders_details
    $orderDetailId = 'OD' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT); // สร้างรหัสรายละเอียดคำสั่งซื้อแบบสุ่ม
    $sql = "INSERT INTO ordersdetails (Orders_De_id, Orders_quantity, Subtotal, Orders_id, Products_id) 
            VALUES (:orderDetailId, :quantity, :subtotal, :orderId, :productId)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':orderDetailId', $orderDetailId);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':subtotal', $subtotal);
    $stmt->bindParam(':orderId', $orderId);
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();

    // ส่งข้อมูลกลับเป็น JSON
    echo json_encode([
        'success' => true,
        'orderId' => $orderId
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => "Database Error: " . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => "Error: " . $e->getMessage()
    ]);
}
?>