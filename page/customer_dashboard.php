<?php
session_start();
require '../system/pdo.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit();
}

try {
    if (!$conn) {
        throw new Exception("เชื่อมต่อบ่ได้");
    }

    // ดึงข้อมูลสินค้า
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    // ดึงข้อมูลรีวิว
    $reviewSql = "SELECT * FROM Products_review";
    $reviewResult = $conn->query($reviewSql);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List </title>
   
    
    <link rel="stylesheet" href="../assets/style.css">
    
</head>

<body>
<?php include_once '../nav/navbar.php'; ?>

    <!-- <header class="bg-dark text-white text-center py-4">
        <h1>Product List</h1>
    </header> -->

    

    <main class="container my-5">
    <div class="row">
        <?php
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $productName = htmlspecialchars($row['Product_fname']) . " " . htmlspecialchars($row['Product_lname']);
                $productImage = isset($row['Product_image']) ? htmlspecialchars($row['Product_image']) : 'placeholder.jpg';
                echo '
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img style="height:100%;" src="https://cdn.discordapp.com/attachments/944535261860208701/1349897428869976144/image.png?ex=67d4c572&is=67d373f2&hm=33236f5009e550d1a5df73f9f2f238ac3e851d63cd8dee2daff9d87b8304dc44&" class="card-img-top" alt="' . $productName . '">
                        <div class="card-body">
                            <h5 class="card-title">' . $productName . '</h5>
                            <p class="card-text">
                                <strong>Stock: ' . htmlspecialchars($row['Stock_quantity']) . ' ชิ้น </strong><br>
                                <strong>Price: ' . htmlspecialchars($row['Product_price']) . ' บาท </strong>
                            </p>
                            <button type="button" class="btn btn-primary" onclick="openPaymentModal(' . $row['Products_id'] . ')">ซื้อสินค้า</button>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<div class="col-12"><p class="text-center">No products found.</p></div>';
        }
        ?>
    </div>

<!-- Modal สำหรับหน้าชำระเงิน -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePaymentModal()">&times;</span>
        <h2>กรอกข้อมูลการจัดส่งและชำระเงิน</h2>
        <form id="paymentForm" action="process_checkout.php" method="POST">
            <!-- ข้อมูลการจัดส่ง -->
            <div class="mb-3">
                <label for="shippingAddress" class="form-label">ที่อยู่จัดส่ง:</label>
                <textarea class="form-control" id="shippingAddress" name="shippingAddress" rows="3" required></textarea>
            </div>

            <!-- จำนวนสินค้า -->
            <div class="mb-3">
                <label for="quantity" class="form-label">จำนวนสินค้า:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            </div>

            <!-- วิธีการชำระเงิน -->
            <div class="mb-3">
                <label class="form-label">วิธีการชำระเงิน:</label>
                <div class="payment-methods">
                    <label class="payment-method">
                        <input type="radio" name="paymentMethod" value="Credit Card" required>
                        <div class="method-content">
                            <span>Credit Card</span>
                        </div>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="paymentMethod" value="Bank Transfer" required>
                        <div class="method-content">
                            <span>Bank Transfer</span>
                        </div>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="paymentMethod" value="Cash on Delivery" required>
                        <div class="method-content">
                            <span>Cash on Delivery</span>
                        </div>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="paymentMethod" value="PayPal" required>
                        <div class="method-content">
                            <span>PayPal</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- ส่งรหัสสินค้า -->
            <input type="hidden" id="productId" name="productId">

            <!-- ปุ่มยืนยัน -->
            <button type="submit" class="btn btn-primary">ยืนยันการสั่งซื้อ</button>
        </form>
    </div>
</div>



            <!-- ปุ่มยืนยัน -->
        
</div>


    </div>
        </div>

        <!-- ส่วนแสดงรีวิว -->
        <div class="mt-5">
            <h2>รีวิวจากผู้ใช้</h2>
            <div class="row">
                <?php
                if ($reviewResult->rowCount() > 0) {
                    while ($review = $reviewResult->fetch(PDO::FETCH_ASSOC)) {
                        echo '
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">คะแนน: ' . htmlspecialchars($review['Rating_product']) . '</h5>
                                    <p class="card-text"><small class="text-muted">' . htmlspecialchars($review['Comment_product']) . '</small></p>
                                    <p class="card-text"><small class="text-muted">หมวดหมู่: ' . htmlspecialchars($review['Preferred_categories']) . '</small></p>
                                    <p class="card-text"><small class="text-muted">เวลา: ' . htmlspecialchars($review['Datetime_product']) . '</small></p>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="col-12"><p>No reviews found.</p></div>';
                }
                ?>
            </div>
        </div>

        <!-- ส่วนเขียนรีวิว -->
        <div class="mt-5">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">เขียนรีวิว</h5>
                </div>
                <div class="card-body">
                    <form action="submit_review.php" method="POST">
                        <div class="mb-3">
                            <label for="rating" class="form-label">คะแนน</label>
                            <select class="form-select" id="rating" name="rating" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">คอมเม้นท์</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="categories" class="form-label">หมวดหมู่</label>
                            <input type="text" class="form-control" id="categories" name="categories" required>
                        </div>
                        <button type="submit" class="btn btn-primary">ยืนยัน</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ลิงก์ไปยังตะกร้าสินค้า -->
        
    </main>

   <?php include '../nav/footer.php'; ?>

    <!-- Bootstrap JS -->
    
            <script>
            function openPaymentModal(productId) {
                    var modal = document.getElementById("paymentModal");
                    modal.style.display = "block";
                    document.getElementById("productId").value = productId; // ตั้งค่ารหัสสินค้าในฟอร์ม
                    console.log("เปิด modal สำหรับสินค้า ID: " + productId); // ตรวจสอบใน Console
                }

                function closePaymentModal() {
                    var modal = document.getElementById("paymentModal");
                    modal.style.display = "none";
                }

                window.onclick = function(event) {
                    var modal = document.getElementById("paymentModal");
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }

                document.getElementById("paymentForm").addEventListener("submit", function(event) {
    event.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ

    // ดึงข้อมูลจากฟอร์ม
    var shippingAddress = document.getElementById("shippingAddress").value;
    var quantity = document.getElementById("quantity").value;
    var selectedMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
    var productId = document.getElementById("productId").value; // รหัสสินค้าจากฟอร์ม

    // ส่งข้อมูลไปยังไฟล์ process_checkout.php โดยใช้ Fetch API
    fetch('process_checkout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            shippingAddress: shippingAddress,
            paymentMethod: selectedMethod,
            productId: productId,
            quantity: quantity
        })
    })
    .then(response => response.json()) // รับข้อมูล JSON จากเซิร์ฟเวอร์
    .then(data => {
        if (data.success) {
            // Redirect ไปยังหน้าติดตามการสั่งซื้อ
            window.location.href = "order_tracking.php?order_id=" + data.orderId;
        } else {
            alert("เกิดข้อผิดพลาด: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("เกิดข้อผิดพลาดในการส่งข้อมูล");
    });
});
        </script>

       

</body>
</html>

<?php
// ปิดการเชื่อมต่อ
$conn = null;
?>