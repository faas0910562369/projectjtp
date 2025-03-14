<?php
session_start();
require '../system/pdo.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$customerId = $_SESSION['user_id']; // รหัสลูกค้าจาก session

try {
    // ดึงข้อมูลคำสั่งซื้อของลูกค้า
    $sql = "SELECT * FROM orders WHERE Cos_id = :customerId ORDER BY Orders_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customerId', $customerId);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตามการสั่งซื้อ</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once '../nav/navbar.php'; ?>

    <main class="container my-5">
        <h1 class="mb-4">ติดตามการสั่งซื้อ</h1>

        <?php if (count($orders) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>เลขที่คำสั่งซื้อ</th>
                            <th>วันที่สั่งซื้อ</th>
                            <th>ที่อยู่จัดส่ง</th>
                            <th>ยอดรวม</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['Orders_id']) ?></td>
                                <td><?= htmlspecialchars($order['Orders_date']) ?></td>
                                <td><?= htmlspecialchars($order['Orders_address']) ?></td>
                                <td><?= number_format($order['Total_price'], 2) ?> บาท</td>
                                <td>
                                    <span class="badge 
                                        <?= $order['Status'] === 'กำลังดำเนินการ' ? 'bg-warning' : 
                                              ($order['Status'] === 'จัดส่งแล้ว' ? 'bg-success' : 'bg-secondary') ?>">
                                        <?= htmlspecialchars($order['Status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                ไม่พบคำสั่งซื้อ
            </div>
        <?php endif; ?>
    </main>

    <?php include '../nav/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>