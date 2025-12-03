<?php
require_once 'config.php';

// Get all products
 $query = "SELECT * FROM produk WHERE Stok > 0 ORDER BY NamaProduk ASC";
 $result = mysqli_query($conn, $query);

// Get all customers
 $query_pelanggan = "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC";
 $result_pelanggan = mysqli_query($conn, $query_pelanggan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Aplikasi POS UMKM</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            min-height: 100vh;
            color: #333;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ff6a00;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-menu {
            display: flex;
            gap: 1.5rem;
            list-style: none;
        }

        .navbar-menu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-menu a:hover {
            background: rgba(255, 106, 0, 0.1);
            color: #ff6a00;
        }

        .navbar-menu a.active {
            background: rgba(255, 106, 0, 0.15);
            color: #ff6a00;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 0.5rem;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: #ff6a00;
            margin: 3px 0;
            transition: 0.3s;
            border-radius: 3px;
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        .products-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #ff6a00;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #f0f0f0;
            position: relative;
            overflow: hidden;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ff9a56, #ff6a00);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .product-card:hover::before {
            transform: scaleX(1);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(255, 106, 0, 0.2);
            border-color: #ff9a56;
        }

        .product-icon {
            font-size: 2.5rem;
            color: #ff9a56;
            margin-bottom: 1rem;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .product-price {
            color: #ff6a00;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .product-stock {
            color: #777;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
        }

        .product-stock i {
            font-size: 0.8rem;
        }

        .cart-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .cart-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-title i {
            color: #ff6a00;
        }

        .customer-select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #f0f0f0;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            background: white;
        }

        .customer-select:focus {
            outline: none;
            border-color: #ff9a56;
        }

        .cart-items {
            max-height: 350px;
            overflow-y: auto;
            margin: 1.5rem 0;
            padding-right: 0.5rem;
        }

        .cart-items::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: #ff9a56;
            border-radius: 10px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f9f9f9;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background: #f5f5f5;
            transform: translateX(5px);
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.3rem;
        }

        .cart-item-price {
            color: #ff6a00;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .cart-item-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: #ff6a00;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            background: #e55a00;
            transform: scale(1.1);
        }

        .qty-display {
            width: 40px;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .remove-btn {
            background: #ff4757;
            color: white;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .remove-btn:hover {
            background: #ff3838;
            transform: scale(1.1);
        }

        .cart-total {
            border-top: 2px solid #f0f0f0;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }

        .total-row span:last-child {
            color: #ff6a00;
        }

        .checkout-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .checkout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 106, 0, 0.3);
        }

        .checkout-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-title i {
            color: #ff6a00;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #f0f0f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #ff9a56;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 106, 0, 0.3);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem 1rem;
            color: #999;
        }

        .empty-cart i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .admin-badge {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 1rem;
        }

        /* Custom Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 2000;
            display: flex;
            align-items: center;
            gap: 1rem;
            transform: translateX(120%);
            transition: transform 0.4s ease;
            max-width: 350px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon.warning {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
        }

        .notification-icon.success {
            background: linear-gradient(135deg, #00d68f 0%, #00a85a 100%);
            color: white;
        }

        .notification-icon.error {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff4757 100%);
            color: white;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #333;
        }

        .notification-message {
            font-size: 0.9rem;
            color: #666;
        }

        .notification-close {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .notification-close:hover {
            background: rgba(0,0,0,0.05);
            color: #666;
        }

        @media (max-width: 1024px) {
            .main-content {
                grid-template-columns: 1fr;
            }

            .cart-section {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .hamburger {
                display: flex;
            }

            .navbar-menu {
                position: fixed;
                left: -100%;
                top: 70px;
                flex-direction: column;
                background-color: white;
                width: 100%;
                text-align: center;
                transition: 0.3s;
                box-shadow: 0 10px 27px rgba(0,0,0,0.05);
                padding: 2rem 0;
                gap: 0.5rem;
                z-index: 99;
            }

            .navbar-menu.active {
                left: 0;
            }

            .navbar-menu a {
                display: block;
                padding: 1rem;
                border-radius: 0;
            }

            .navbar-menu li {
                margin: 0;
            }

            .notification {
                right: 10px;
                left: 10px;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-cash-register"></i> POS UMKM
        </div>
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="navbar-menu" id="navbarMenu">
            <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="index.php" class="active"><i class="fas fa-shopping-cart"></i> Kasir</a></li>
            <li><a href="stok.php"><i class="fas fa-boxes"></i> Stok</a></li>
            <li><a href="pelanggan.php"><i class="fas fa-users"></i> Pelanggan</a></li>
            <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
            <?php if (isAdmin()): ?>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a><span class="admin-badge">Admin</span></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container">
        <div class="main-content">
            <!-- Products Section -->
            <div class="products-section">
                <h2 class="section-title"><i class="fas fa-th-large"></i> Daftar Produk</h2>
                <div class="products-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="product-card" onclick='addToCart(<?php echo json_encode($row); ?>)'>
                            <div class="product-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="product-name"><?php echo htmlspecialchars($row['NamaProduk']); ?></div>
                            <div class="product-price">Rp <?php echo number_format($row['Harga'], 0, ',', '.'); ?></div>
                            <div class="product-stock">
                                <i class="fas fa-cube"></i> Stok: <?php echo $row['Stok']; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Cart Section -->
            <div class="cart-section">
                <h2 class="cart-title"><i class="fas fa-shopping-basket"></i> Keranjang</h2>
                
                <select class="customer-select" id="pelangganSelect">
                    <option value="0">-- Pelanggan Umum --</option>
                    <?php while ($pelanggan = mysqli_fetch_assoc($result_pelanggan)): ?>
                        <option value="<?php echo $pelanggan['pelanggan_id']; ?>">
                            <?php echo htmlspecialchars($pelanggan['nama_pelanggan']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <div class="cart-items" id="cartItems">
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Keranjang kosong</p>
                    </div>
                </div>

                <div class="cart-total">
                    <div class="total-row">
                        <span>Total:</span>
                        <span id="totalPrice">Rp 0</span>
                    </div>
                </div>

                <button class="checkout-btn" id="checkoutBtn" onclick="openPaymentModal()" disabled>
                    <i class="fas fa-money-bill-wave"></i> Bayar
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <h3 class="modal-title"><i class="fas fa-calculator"></i> Pembayaran</h3>
            <div class="form-group">
                <label class="form-label">Total Belanja:</label>
                <input type="text" class="form-input" id="totalBelanja" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Bayar:</label>
                <input type="number" class="form-input" id="jumlahBayar" placeholder="Masukkan jumlah bayar">
            </div>
            <div class="form-group">
                <label class="form-label">Kembalian:</label>
                <input type="text" class="form-input" id="kembalian" readonly>
            </div>
            <div class="btn-group">
                <button class="btn btn-secondary" onclick="closePaymentModal()">Batal</button>
                <button class="btn btn-primary" onclick="processPayment()">Proses</button>
            </div>
        </div>
    </div>

    <!-- Custom Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon warning">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="notification-content">
            <div class="notification-title">Stok Tidak Mencukupi</div>
            <div class="notification-message" id="notificationMessage">Maaf, stok produk ini tidak mencukupi.</div>
        </div>
        <button class="notification-close" onclick="hideNotification()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
        let cart = [];

        function toggleMenu() {
            const navbarMenu = document.getElementById('navbarMenu');
            navbarMenu.classList.toggle('active');
        }

        function showNotification(title, message, type = 'warning') {
            const notification = document.getElementById('notification');
            const titleElement = document.querySelector('.notification-title');
            const messageElement = document.getElementById('notificationMessage');
            const iconElement = document.querySelector('.notification-icon i');
            const iconContainer = document.querySelector('.notification-icon');
            
            titleElement.textContent = title;
            messageElement.textContent = message;
            
            // Update icon and color based on notification type
            if (type === 'success') {
                iconElement.className = 'fas fa-check-circle';
                iconContainer.className = 'notification-icon success';
            } else if (type === 'error') {
                iconElement.className = 'fas fa-exclamation-circle';
                iconContainer.className = 'notification-icon error';
            } else { // default warning
                iconElement.className = 'fas fa-exclamation-triangle';
                iconContainer.className = 'notification-icon warning';
            }
            
            notification.classList.add('show');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                hideNotification();
            }, 5000);
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.remove('show');
        }

        function addToCart(product) {
            const existingItem = cart.find(item => item.produk_id === product.produk_id);
            
            if (existingItem) {
                if (existingItem.quantity < product.Stok) {
                    existingItem.quantity++;
                } else {
                    showNotification('Stok Tidak Mencukupi', `Maaf, stok untuk "${product.NamaProduk}" hanya tersisa ${product.Stok} unit.`);
                    return;
                }
            } else {
                cart.push({
                    produk_id: product.produk_id,
                    NamaProduk: product.NamaProduk,
                    Harga: parseFloat(product.Harga),
                    Stok: parseInt(product.Stok),
                    quantity: 1
                });
            }
            
            renderCart();
        }

        function updateQuantity(index, change) {
            const item = cart[index];
            const newQty = item.quantity + change;
            
            if (newQty > 0 && newQty <= item.Stok) {
                item.quantity = newQty;
                renderCart();
            } else if (newQty > item.Stok) {
                showNotification('Stok Tidak Mencukupi', `Maaf, stok untuk "${item.NamaProduk}" hanya tersisa ${item.Stok} unit.`);
            }
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            const cartItemsDiv = document.getElementById('cartItems');
            const checkoutBtn = document.getElementById('checkoutBtn');
            
            if (cart.length === 0) {
                cartItemsDiv.innerHTML = `
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Keranjang kosong</p>
                    </div>
                `;
                checkoutBtn.disabled = true;
            } else {
                cartItemsDiv.innerHTML = cart.map((item, index) => `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.NamaProduk}</div>
                            <div class="cart-item-price">Rp ${item.Harga.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="cart-item-controls">
                            <button class="qty-btn" onclick="updateQuantity(${index}, -1)">-</button>
                            <span class="qty-display">${item.quantity}</span>
                            <button class="qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                            <button class="remove-btn" onclick="removeFromCart(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
                checkoutBtn.disabled = false;
            }
            
            updateTotal();
        }

        function updateTotal() {
            const total = cart.reduce((sum, item) => sum + (item.Harga * item.quantity), 0);
            document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function openPaymentModal() {
            const total = cart.reduce((sum, item) => sum + (item.Harga * item.quantity), 0);
            document.getElementById('totalBelanja').value = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('jumlahBayar').value = '';
            document.getElementById('kembalian').value = '';
            document.getElementById('paymentModal').classList.add('active');
            document.getElementById('jumlahBayar').focus();
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.remove('active');
        }

        document.getElementById('jumlahBayar').addEventListener('input', function() {
            const total = cart.reduce((sum, item) => sum + (item.Harga * item.quantity), 0);
            const bayar = parseFloat(this.value) || 0;
            const kembalian = bayar - total;
            
            if (kembalian >= 0) {
                document.getElementById('kembalian').value = 'Rp ' + kembalian.toLocaleString('id-ID');
            } else {
                document.getElementById('kembalian').value = 'Kurang: Rp ' + Math.abs(kembalian).toLocaleString('id-ID');
            }
        });

        function processPayment() {
            const total = cart.reduce((sum, item) => sum + (item.Harga * item.quantity), 0);
            const bayar = parseFloat(document.getElementById('jumlahBayar').value) || 0;
            
            if (bayar < total) {
                showNotification('Pembayaran Tidak Mencukupi', 'Jumlah bayar kurang dari total belanja!', 'error');
                return;
            }
            
            const pelangganId = document.getElementById('pelangganSelect').value;
            
            // Send data to server
            const formData = new FormData();
            formData.append('pelanggan_id', pelangganId);
            formData.append('total_harga', total);
            formData.append('jumlah_bayar', bayar);
            formData.append('cart', JSON.stringify(cart));
            
            fetch('process_sale.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Transaksi Berhasil', 'Transaksi Anda telah berhasil diproses.', 'success');
                    // Open receipt in new window
                    window.open('cetak_nota.php?id=' + data.penjualan_id, '_blank');
                    // Reset cart
                    cart = [];
                    renderCart();
                    closePaymentModal();
                    document.getElementById('pelangganSelect').value = '0';
                } else {
                    showNotification('Transaksi Gagal', 'Transaksi gagal: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Terjadi Kesalahan', 'Terjadi kesalahan: ' + error, 'error');
            });
        }
    </script>
</body>
</html>
