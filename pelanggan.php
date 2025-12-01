<?php
require_once 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdmin()) {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $nama = sanitize($_POST['nama_pelanggan']);
            $alamat = sanitize($_POST['alamat']);
            $telepon = sanitize($_POST['nomor_telepon']);
            
            $query = "INSERT INTO pelanggan (nama_pelanggan, alamat, nomor_telepon) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sss", $nama, $alamat, $telepon);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "Pelanggan berhasil ditambahkan!";
            } else {
                $error_msg = "Gagal menambahkan pelanggan!";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = intval($_POST['pelanggan_id']);
            $nama = sanitize($_POST['nama_pelanggan']);
            $alamat = sanitize($_POST['alamat']);
            $telepon = sanitize($_POST['nomor_telepon']);
            
            $query = "UPDATE pelanggan SET nama_pelanggan = ?, alamat = ?, nomor_telepon = ? WHERE pelanggan_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sssi", $nama, $alamat, $telepon, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "Data pelanggan berhasil diubah!";
            } else {
                $error_msg = "Gagal mengubah data pelanggan!";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = intval($_POST['pelanggan_id']);
            
            // Check if customer has transactions
            $check_query = "SELECT COUNT(*) as total FROM penjualan WHERE pelanggan_id = ?";
            $check_stmt = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($check_stmt, "i", $id);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            $has_transactions = mysqli_fetch_assoc($check_result)['total'] > 0;
            
            if ($has_transactions) {
                $error_msg = "Tidak dapat menghapus pelanggan yang memiliki riwayat transaksi!";
            } else {
                $query = "DELETE FROM pelanggan WHERE pelanggan_id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success_msg = "Pelanggan berhasil dihapus!";
                } else {
                    $error_msg = "Gagal menghapus pelanggan!";
                }
            }
        }
    }
}

// Get all customers
 $query = "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC";
 $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pelanggan - Aplikasi POS UMKM</title>
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
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
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
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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

        .btn-action {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-edit {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 106, 0, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(238, 90, 36, 0.3);
        }

        .btn-action:disabled {
            background: #ccc;
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

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #f0f0f0;
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.3s ease;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: #ff9a56;
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
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

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(238, 90, 36, 0.3);
        }

        .admin-badge {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 1rem;
        }

        @media (max-width: 768px) {
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

            .table {
                font-size: 0.9rem;
            }

            .page-title {
                font-size: 1.8rem;
            }

            .btn-action {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
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
            <li><a href="index.php"><i class="fas fa-shopping-cart"></i> Kasir</a></li>
            <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
            <li><a href="stok.php"><i class="fas fa-boxes"></i> Stok</a></li>
            <li><a href="pelanggan.php" class="active"><i class="fas fa-users"></i> Pelanggan</a></li>
            <?php if (isAdmin()): ?>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a><span class="admin-badge">Admin</span></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container">
        <h1 class="page-title"><i class="fas fa-users"></i> Manajemen Pelanggan</h1>

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
                <i class="fas fa-user-plus"></i> Tambah Pelanggan Baru
            </button>
            <?php if (!isAdmin()): ?>
                <p class="info-text">
                    <i class="fas fa-info-circle"></i> Login sebagai admin untuk mengelola pelanggan
                </p>
            <?php endif; ?>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Alamat</th>
                            <th>Nomor Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['nama_pelanggan']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                <td><?php echo htmlspecialchars($row['nomor_telepon']); ?></td>
                                <td>
                                    <button class="btn-action btn-edit" onclick='openEditModal(<?php echo json_encode($row); ?>)' <?php echo !isAdmin() ? 'disabled' : ''; ?>>
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn-action btn-delete" onclick='openDeleteModal(<?php echo json_encode($row); ?>)' <?php echo !isAdmin() ? 'disabled' : ''; ?>>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal" id="addModal">
        <div class="modal-content">
            <h3 class="modal-title"><i class="fas fa-user-plus"></i> Tambah Pelanggan Baru</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label class="form-label">Nama Pelanggan:</label>
                    <input type="text" name="nama_pelanggan" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat:</label>
                    <textarea name="alamat" class="form-textarea" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Telepon:</label>
                    <input type="text" name="nomor_telepon" class="form-input" required>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h3 class="modal-title"><i class="fas fa-user-edit"></i> Edit Data Pelanggan</h3>
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="pelanggan_id" id="edit_pelanggan_id">
                <div class="form-group">
                    <label class="form-label">Nama Pelanggan:</label>
                    <input type="text" name="nama_pelanggan" id="edit_nama_pelanggan" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat:</label>
                    <textarea name="alamat" id="edit_alamat" class="form-textarea" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Telepon:</label>
                    <input type="text" name="nomor_telepon" id="edit_nomor_telepon" class="form-input" required>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <h3 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h3>
            <p style="margin-bottom: 1.5rem;">Apakah Anda yakin ingin menghapus pelanggan <strong id="delete_nama"></strong>?</p>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="pelanggan_id" id="delete_pelanggan_id">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const navbarMenu = document.getElementById('navbarMenu');
            navbarMenu.classList.toggle('active');
        }

        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
        }

        function openEditModal(customer) {
            document.getElementById('edit_pelanggan_id').value = customer.pelanggan_id;
            document.getElementById('edit_nama_pelanggan').value = customer.nama_pelanggan;
            document.getElementById('edit_alamat').value = customer.alamat;
            document.getElementById('edit_nomor_telepon').value = customer.nomor_telepon;
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        function openDeleteModal(customer) {
            document.getElementById('delete_pelanggan_id').value = customer.pelanggan_id;
            document.getElementById('delete_nama').textContent = customer.nama_pelanggan;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
    </script>
</body>
</html>
