<?php
require_once 'config.php';

 $search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
 $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
 $limit = 10;
 $offset = ($page - 1) * $limit;

// Build query
 $where = "";
if ($search) {
    $where = "WHERE p.penjualan_id LIKE '%$search%' OR pel.nama_pelanggan LIKE '%$search%'";
}

// Get total records
 $count_query = "SELECT COUNT(*) as total FROM penjualan p LEFT JOIN pelanggan pel ON p.pelanggan_id = pel.pelanggan_id $where";
 $count_result = mysqli_query($conn, $count_query);
 $total_records = mysqli_fetch_assoc($count_result)['total'];
 $total_pages = ceil($total_records / $limit);

// Get transactions
 $query = "SELECT p.*, pel.nama_pelanggan 
          FROM penjualan p 
          LEFT JOIN pelanggan pel ON p.pelanggan_id = pel.pelanggan_id 
          $where
          ORDER BY p.penjualan_id DESC 
          LIMIT $limit OFFSET $offset";
 $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Aplikasi POS UMKM</title>
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

        .search-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid #f0f0f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #ff9a56;
        }

        .search-btn {
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 106, 0, 0.3);
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 1.5rem;
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

        .btn-print {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-print:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 106, 0, 0.3);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .page-link {
            padding: 0.5rem 1rem;
            background: white;
            border: 2px solid #ff9a56;
            color: #ff6a00;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .page-link:hover, .page-link.active {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 106, 0, 0.3);
        }

        .admin-badge {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 1rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #999;
        }

        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
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

            .table th, .table td {
                padding: 0.75rem 0.5rem;
            }

            .page-title {
                font-size: 1.8rem;
            }

            .search-bar {
                flex-direction: column;
            }

            .search-btn {
                width: 100%;
                justify-content: center;
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
            <li><a href="riwayat.php" class="active"><i class="fas fa-history"></i> Riwayat</a></li>
            <li><a href="stok.php"><i class="fas fa-boxes"></i> Stok</a></li>
            <li><a href="pelanggan.php"><i class="fas fa-users"></i> Pelanggan</a></li>
            <?php if (isAdmin()): ?>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a><span class="admin-badge">Admin</span></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container">
        <h1 class="page-title"><i class="fas fa-history"></i> Riwayat Transaksi</h1>

        <div class="card">
            <form method="GET" class="search-bar">
                <input type="text" name="search" class="search-input" placeholder="Cari berdasarkan ID transaksi atau nama pelanggan..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><strong>#<?php echo str_pad($row['penjualan_id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                    <td><?php echo date('d M Y', strtotime($row['tanggal_penjualan'])); ?></td>
                                    <td><?php echo $row['nama_pelanggan'] ? htmlspecialchars($row['nama_pelanggan']) : 'Pelanggan Umum'; ?></td>
                                    <td><strong style="color: #ff6a00;">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></strong></td>
                                    <td>
                                        <a href="cetak_nota.php?id=<?php echo $row['penjualan_id']; ?>" target="_blank" class="btn-print">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-receipt"></i>
                                        <p>Tidak ada data transaksi yang ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
                           class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const navbarMenu = document.getElementById('navbarMenu');
            navbarMenu.classList.toggle('active');
        }
    </script>
</body>
</html>
