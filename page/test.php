<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .cart-wrapper {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 40px 0;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-2px);
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            position: sticky;
            top: 20px;
        }

        .checkout-btn {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border: none;
            transition: transform 0.2s;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #4f46e5, #4338ca);
        }

        .remove-btn {
            color: #dc2626;
            cursor: pointer;
            transition: all 0.2s;
        }

        .remove-btn:hover {
            color: #991b1b;
        }

        .quantity-btn {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: #f3f4f6;
            border: none;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #e5e7eb;
        }

        .discount-badge {
            background: #dcfce7;
            color: #166534;
            font-size: 0.875rem;
            padding: 4px 8px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="cart-wrapper">
        <div class="container">
            <div class="row g-4">
                <!-- Cart Items Section -->
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Shopping Cart</h4>
                        <span class="text-muted">3 items</span>
                    </div>

                    <!-- Product Cards -->
                    <div class="d-flex flex-column gap-3">
                        <!-- Product 1 -->
                        <div class="product-card p-3 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="https://via.placeholder.com/100" alt="Product" class="product-image">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">Wireless Headphones</h6>
                                    <p class="text-muted mb-0">Black | Premium Series</p>
                                    <span class="discount-badge mt-2">20% OFF</span>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="quantity-btn" onclick="updateQuantity(1, -1)">-</button>
                                        <input type="number" class="quantity-input" value="1" min="1">
                                        <button class="quantity-btn" onclick="updateQuantity(1, 1)">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">$129.99</span>
                                </div>
                                <div class="col-md-1">
                                    <i class="bi bi-trash remove-btn"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="product-card p-3 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="https://via.placeholder.com/100" alt="Product" class="product-image">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">Smart Watch</h6>
                                    <p class="text-muted mb-0">Silver | Series 7</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="quantity-btn" onclick="updateQuantity(2, -1)">-</button>
                                        <input type="number" class="quantity-input" value="1" min="1">
                                        <button class="quantity-btn" onclick="updateQuantity(2, 1)">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">$299.99</span>
                                </div>
                                <div class="col-md-1">
                                    <i class="bi bi-trash remove-btn"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Product 3 -->
                        <div class="product-card p-3 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="https://via.placeholder.com/100" alt="Product" class="product-image">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">Wireless Charger</h6>
                                    <p class="text-muted mb-0">White | 15W Fast Charge</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="quantity-btn" onclick="updateQuantity(3, -1)">-</button>
                                        <input type="number" class="quantity-input" value="1" min="1">
                                        <button class="quantity-btn" onclick="updateQuantity(3, 1)">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">$49.99</span>
                                </div>
                                <div class="col-md-1">
                                    <i class="bi bi-trash remove-btn"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="col-lg-4">
                    <div class="summary-card p-4 shadow-sm">
                        <h5 class="mb-4">Order Summary</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span>$479.97</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Discount</span>
                            <span class="text-success">-$26.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span>$5.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold">$458.97</span>
                        </div>

                        <!-- Promo Code -->
                        <div class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Promo code">
                                <button class="btn btn-outline-secondary" type="button">Apply</button>
                            </div>
                        </div>

                        <button class="btn btn-primary checkout-btn w-100 mb-3">
                            Proceed to Checkout
                        </button>
                        
                        <div class="d-flex justify-content-center gap-2">
                            <i class="bi bi-shield-check text-success"></i>
                            <small class="text-muted">Secure checkout</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQuantity(productId, change) {
            const input = event.target.parentElement.querySelector('.quantity-input');
            let value = parseInt(input.value) + change;
            if (value >= 1) {
                input.value = value;
            }
        }
    </script>
</body>
</html>