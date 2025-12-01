<?php
require_once 'config.php';

// Handle form submission for add/edit product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdmin()) {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $nama = sanitize($_POST['nama_produk']);
            $harga = floatval($_POST['harga']);
            $stok = intval($_POST['stok']);
            
            $query = "INSERT INTO produk (NamaProduk, Harga, Stok) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sdi", $nama, $harga, $stok);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "Produk berhasil ditambahkan!";
            } else {
                $error_msg = "Gagal menambahkan produk!";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = intval($_POST['produk_id']);
            $stok = intval($_POST['stok']);
            
            $query = "UPDATE produk SET Stok = ? WHERE ProdukID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ii", $stok, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "Stok berhasil diubah!";
            } else {
                $error_msg = "Gagal mengubah stok!";
            }
        }
    }
}

// Get all products
 $query = "SELECT * FROM produk ORDER BY NamaProduk ASC";
 $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stok - Aplikasi POS UMKM</title>
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

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .page-title {
            color: white;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 106, 0, 0.3);
        }

        .btn-add:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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

        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
        }

        .table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: #f9f9f9;
            transform: translateX(5px);
        }

        .btn-edit {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-edit:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 106, 0, 0.3);
        }

        .btn-edit:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .stock-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .stock-high {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .stock-medium {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .stock-low {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
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
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-title i {
            color: #ff6a00;
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

        .admin-badge {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 1rem;
        }

        .info-text {
            color: #777;
            font-size: 0.9rem;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 0.9rem;
            }

            .navbar-menu {
                gap: 0.5rem;
                font-size: 0.9rem;
            }

            .page-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-cash-register"></i> POS UMKM
        </div>
        <ul class="navbar-menu">
            <li><a href="index.php"><i class="fas fa-shopping-cart"></i> Kasir</a></li>
            <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
            <li><a href="stok.php" class="active"><i class="fas fa-boxes"></i> Stok</a></li>
            <li><a href="pelanggan.php"><i class="fas fa-users"></i> Pelanggan</a></li>
            <?php if (isAdmin()): ?>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a><span class="admin-badge">Admin</span></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container">
        <h1 class="page-title"><i class="fas fa-boxes"></i> Manajemen Stok Produk</h1>

        <?php if (isset($success_msg)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_msg)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <button class="btn-add" onclick="openAddModal()" <?php echo !isAdmin() ? 'disabled' : ''; ?>>
                <i class="fas fa-plus"></i> Tambah Produk Baru
            </button>
            <?php if (!isAdmin()): ?>
                <p class="info-text">
                    <i class="fas fa-info-circle"></i> Login sebagai admin untuk menambah produk
                </p>
            <?php endif; ?>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok Saat Ini</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): 
                            $stok = $row['Stok'];
                            if ($stok > 20) {
                                $badge_class = 'stock-high';
                                $status = 'Aman';
                            } elseif ($stok > 5) {
                                $badge_class = 'stock-medium';
                                $status = 'Menipis';
                            } else {
                                $badge_class = 'stock-low';
                                $status = 'Kritis';
                            }
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['NamaProduk']); ?></strong></td>
                                <td style="color: #ff6a00; font-weight: 600;">Rp <?php echo number_format($row['Harga'], 0, ',', '.'); ?></td>
                                <td><strong><?php echo $row['Stok']; ?></strong></td>
                                <td><span class="stock-badge <?php echo $badge_class; ?>"><?php echo $status; ?></span></td>
                                <td>
                                    <button class="btn-edit" onclick='openEditModal(<?php echo json_encode($row); ?>)' <?php echo !isAdmin() ? 'disabled' : ''; ?>>
                                        <i class="fas fa-edit"></i> Ubah Stok
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal" id="addModal">
        <div class="modal-content">
            <h3 class="modal-title"><i class="fas fa-plus"></i> Tambah Produk Baru</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label class="form-label">Nama Produk:</label>
                    <input type="text" name="nama_produk" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga:</label>
                    <input type="number" name="harga" class="form-input" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Stok Awal:</label>
                    <input type="number" name="stok" class="form-input" required>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Stock Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h3 class="modal-title"><i class="fas fa-edit"></i> Ubah Stok Produk</h3>
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="produk_id" id="edit_produk_id">
                <div class="form-group">
                    <label class="form-label">Nama Produk:</label>
                    <input type="text" id="edit_nama_produk" class="form-input" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Stok Saat Ini:</label>
                    <input type="text" id="edit_stok_current" class="form-input" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Ubah Stok Menjadi:</label>
                    <input type="number" name="stok" id="edit_stok" class="form-input" required>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
        }

        function openEditModal(product) {
            document.getElementById('edit_produk_id').value = product.ProdukID;
            document.getElementById('edit_nama_produk').value = product.NamaProduk;
            document.getElementById('edit_stok_current').value = product.Stok;
            document.getElementById('edit_stok').value = product.Stok;
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }
    </script>
</body>
</html>