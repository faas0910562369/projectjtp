<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <!-- นำเข้า Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- นำเข้า DaisyUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/dist/full.css" rel="stylesheet" type="text/css" />
    <style>
        .navbar {
            background: linear-gradient(135deg, #6a11cb, #2575fc); /* Gradient สีม่วง-น้ำเงิน */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 1rem 2rem;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #ffffff !important;
        }
        .nav-link {
            font-size: 1.1rem;
            margin-right: 20px;
            color: #ffffff !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #ffdd57 !important; /* สีเมื่อ hover */
        }
        .btn-logout {
            margin-left: auto;
            background-color: #ff3860;
            border: none;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        .btn-logout:hover {
            background-color: #ff1c4a; /* สีเมื่อ hover */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark container rounded mt-3">
        <a class="navbar-brand" href="/page/customer_dashboard.php">HOME</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
               
                
            </ul>
            <div class="d-flex">
                <a href="/page/logout.php" class="btn btn-logout text-light">ออกจากระบบ</a>
            </div>
        </div>
    </nav>

    <!-- นำเข้า Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>