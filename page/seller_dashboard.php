<?php
session_start();
require '../system/pdo.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

// ตรวจสอบสิทธิ์ผู้ใช้
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: login.php");
    exit();

    
}

try {
    // เชื่อมต่อฐานข้อมูล
    if (!$conn) {
        throw new Exception("เชื่อมต่อฐานข้อมูลไม่ได้");
    }

    // ดึงข้อมูล Seller
    $sellerId = $_SESSION['user_id'];
    $sellerSql = "SELECT * FROM sellers WHERE user_id = :user_id";
    $sellerStmt = $conn->prepare($sellerSql);
    $sellerStmt->bindParam(':user_id', $sellerId);
    $sellerStmt->execute();
    $seller = $sellerStmt->fetch(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสินค้าของ Seller
    $productSql = "SELECT * FROM products WHERE Sellers_id = :seller_id";
    $productStmt = $conn->prepare($productSql);
    $productStmt->bindParam(':seller_id', $seller['Sellers_id'], PDO::PARAM_INT); // ใช้ Sellers_id จากตาราง sellers
    $productStmt->execute();
    $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);


    // ดึงข้อมูลคำสั่งซื้อของ Seller
        $sql = "SELECT * FROM Orders ORDER BY Orders_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);



    // ดึงข้อมูลรีวิวสินค้าของ Seller
    $sql = "SELECT * FROM products_review ORDER BY Datetime_product DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // ดึงข้อมูลการจัดส่งสินค้าของ Seller
    $sql = "SELECT * FROM shipments ORDER BY Shipments_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $shipments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    
    <div class="container my-5">
        <!-- ส่วนแสดงข้อมูล Seller -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">ข้อมูลผู้ขาย</h5>
            </div>
            <div class="card-body">
                <?php if ($seller): ?>
                    <p><strong>ชื่อ:</strong> <?= htmlspecialchars($seller['Sellers_fname']) ?> <?= htmlspecialchars($seller['Sellers_lname']) ?></p>
                    <p><strong>อีเมล:</strong> <?= htmlspecialchars($seller['Sellers_email']) ?></p>
                    <p><strong>โทรศัพท์:</strong> <?= htmlspecialchars($seller['Sellers_phone']) ?></p>
                    <p><strong>ที่อยู่:</strong> <?= htmlspecialchars($seller['Sellers_address']) ?></p>
                <?php else: ?>
                    <p class="text-danger">ไม่พบข้อมูลผู้ขาย</p>
                <?php endif; ?>
                <a href="/page/logout.php" class="btn btn-danger">ออกจากระบบ</a>

            </div>
        </div>

        <!-- ส่วนจัดการสินค้า -->
        <div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">จัดการสินค้า</h5>
    </div>
    <div class="card-body">
        <!-- Search Bar -->
        <div class="mb-4">
            <input type="text" id="searchProduct" class="form-control" placeholder="ค้นหาสินค้า...">
        </div>

        <?php if (is_array($products) && count($products) > 0): ?>
         <div class="row" id="productList">
        <?php foreach ($products as $product): ?>
            <?php
            // ตรวจสอบว่าคีย์ในอาร์เรย์มีค่าหรือไม่
            $productId = $product['Products_id'] ?? '';
            $productFname = $product['Product_fname'] ?? '';
            $productLname = $product['Product_lname'] ?? '';
            $stockQuantity = $product['Stock_quantity'] ?? 0;
            $productPrice = $product['Product_price'] ?? 0;
            $category = $product['Category'] ?? '';
            ?>
            <div class="col-md-4 mb-4 product-item">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?= htmlspecialchars($productFname) ?> <?= htmlspecialchars($productLname) ?>
                        </h5>
                        <p class="card-text">
                            <strong>สต็อก:</strong> <?= htmlspecialchars($stockQuantity) ?> ชิ้น<br>
                            <strong>ราคา:</strong> <?= htmlspecialchars($productPrice) ?> บาท<br>
                            <strong>หมวดหมู่:</strong> <?= htmlspecialchars($category) ?>
                        </p>
                        <div class="d-flex justify-content-between">
                            <a href="edit_product.php?id=<?= $productId ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                            <a href="delete_product.php?id=<?= $productId ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ที่ต้องการลบสินค้านี้?');">ลบ</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-4">
                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                ไม่มีสินค้าในระบบ
            </div>
        <?php endif; ?>

        <!-- Add Product Button -->
        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addProductModal" id="addProductModalLabel">เพิ่มสินค้าใหม่</button>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">เพิ่มสินค้าใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Product Form -->
                <form action="add_product.php" method="POST">
                    <div class="mb-3">
                        <label for="productFname" class="form-label">ชื่อสินค้า</label>
                        <input type="text" class="form-control" id="productFname" name="productFname" required>
                    </div>
                    <div class="mb-3">
                        <label for="productLname" class="form-label">นามสกุลสินค้า</label>
                        <input type="text" class="form-control" id="productLname" name="productLname" required>
                    </div>
                    <div class="mb-3">
                        <label for="stockQuantity" class="form-label">จำนวนสต็อก</label>
                        <input type="number" class="form-control" id="stockQuantity" name="stockQuantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">ราคา</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">หมวดหมู่</label>
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript for Search Functionality -->
<!-- <script>
    document.getElementById('searchProduct').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const productItems = document.querySelectorAll('.product-item');

        productItems.forEach(item => {
            const productName = item.querySelector('.card-title').textContent.toLowerCase();
            if (productName.includes(searchValue)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script> -->

        <!-- ส่วนแสดงคำสั่งซื้อ -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">คำสั่งซื้อ</h5>
            </div>
            <div class="card-body">
            
    <table class="table table-striped">
        <thead>
            <tr>
            <th>Order ID</th>
            <th>วันที่สั่งซื้อ</th>
            <th>รายละเอียด</th>
            <th>ราคารวม</th>
            <th>ที่อยู่จัดส่ง</th>
            <th>Customer ID</th>
            <th>Payments ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                <td><?= $order['Orders_id'] ?></td>
            <td><?= $order['Orders_date'] ?></td>
            <td><?= $order['Purchase_history'] ?></td>
            <td><?= number_format($order['Total_price'], 2) ?> บาท</td>
            <td><?= $order['Orders_address'] ?></td>
            <td><?= $order['Cos_id'] ?></td>
            <td><?= $order['Payments_id'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<!--  -->

            </div>
        </div>

        <!-- ส่วนแสดงรีวิวสินค้า -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">รีวิวสินค้า</h5>
            </div>
               
            <div class="card-body">
                <?php foreach ($reviews as $review): ?>
                            <tr>
                    <td><?= $review['Products_review_id'] ?></td>
                    <td><?= $review['Rating_product'] ?> ⭐</td>
                    <td><?= $review['Comment_product'] ?></td>
                    <td><?= $review['Preferred_categories'] ?></td>
                    <td><?= $review['Datetime_product'] ?></td>
                    <td><?= $review['Cos_id'] ?></td>
                    <td><?= $review['Products_id'] ?></td>
                </tr>
                        <hr>
                    <?php endforeach; ?>
               
            </div>
        </div>

        <!-- ส่วนแสดงการจัดส่งสินค้า -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">การจัดส่งสินค้า</h5>
            </div>
            <div class="card-body">
                
                    <table class="table">
                        <thead>
                            <tr>
                                <th>รหัสการจัดส่ง</th>
                                <th>รหัสคำสั่งซื้อ</th>
                                <th>วันที่จัดส่ง</th>
                                <th>สถานะ</th>
                                <th>Customer ID</th>
                                <th>Order ID</th>
                                <th>Status ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shipments as $shipment): ?>
                                <tr>
                                    <td><?= $shipment['Shipments_id'] ?></td>
                                    <td><?= $shipment['Tracking_number'] ?></td>
                                    <td><?= $shipment['Shipments_date'] ?></td>
                                    <td><?= $shipment['Deliver_status'] ?></td>
                                    <td><?= $shipment['Cos_id'] ?></td>
                                    <td><?= $shipment['Orders_id'] ?></td>
                                    <td><?= $shipment['Status_id'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
               
            </div>
        </div>
    </div>

    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

