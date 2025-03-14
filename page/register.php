<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <!-- เพิ่ม Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error { color: red; }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .btn-register {
            width: 100%;
            margin-top: 10px;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>สมัครสมาชิก</h2>
        <form action="register_check.php" method="post" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="fname" class="form-label">ชื่อ:</label>
                <input type="text" name="fname" id="fname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">นามสกุล:</label>
                <input type="text" name="lname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">โทรศัพท์:</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">ที่อยู่:</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="user_type" class="form-label">ประเภทผู้ใช้:</label>
                <select name="user_type" class="form-control" required>
                    <option value="customer">ลูกค้า</option>
                    <option value="seller">ผู้ขาย</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-register">สมัครสมาชิก</button>
        </form>
        <div class="login-link">
            <a href="login.php">เข้าสู่ระบบ</a>
        </div>
    </div>

    <!-- เพิ่ม Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                alert('รหัสผ่านไม่ตรงกัน');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>