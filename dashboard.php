<?php
require_once 'config.php';

// Get total revenue
 $query_revenue = "SELECT SUM(total_harga) as total FROM penjualan";
 $result_revenue = mysqli_query($conn, $query_revenue);
 $revenue = mysqli_fetch_assoc($result_revenue)['total'] ?? 0;

// Get total transactions
 $query_trans = "SELECT COUNT(*) as total FROM penjualan";
 $result_trans = mysqli_query($conn, $query_trans);
 $total_transactions = mysqli_fetch_assoc($result_trans)['total'];

// Get recent transactions
 $query_recent = "SELECT p.*, pel.nama_pelanggan 
                 FROM penjualan p 
                 LEFT JOIN pelanggan pel ON p.pelanggan_id = pel.pelanggan_id 
                 ORDER BY p.penjualan_id DESC LIMIT 5";
 $result_recent = mysqli_query($conn, $query_recent);

// Get last 7 days sales
 $query_7days = "SELECT DATE(tanggal_penjualan) as tanggal, SUM(total_harga) as total 
                FROM penjualan 
                WHERE tanggal_penjualan >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(tanggal_penjualan)
                ORDER BY tanggal ASC";
 $result_7days = mysqli_query($conn, $query_7days);
 $sales_data = [];
while ($row = mysqli_fetch_assoc($result_7days)) {
    $sales_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aplikasi POS UMKM</title>
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ff9a56, #ff6a00);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(255, 106, 0, 0.2);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .stat-icon.revenue {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
        }

        .stat-icon.transactions {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
        }

        .stat-info h3 {
            color: #777;
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            color: #333;
            font-size: 2rem;
            font-weight: 700;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
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

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-title i {
            color: #ff6a00;
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem;
            background: #f9f9f9;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .transaction-item:hover {
            background: #f5f5f5;
            transform: translateX(5px);
        }

        .transaction-info h4 {
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .transaction-info p {
            color: #777;
            font-size: 0.9rem;
        }

        .transaction-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ff6a00;
        }

        .chart-container {
            width: 100%;
            height: 320px;
            position: relative;
        }

        .admin-badge {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 1rem;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .navbar-menu {
                gap: 0.5rem;
                font-size: 0.9rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
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
            <li><a href="dashboard.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
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
        <h1 class="page-title"><i class="fas fa-chart-line"></i> Dashboard</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon revenue">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Pendapatan</h3>
                    <div class="stat-value">Rp <?php echo number_format($revenue, 0, ',', '.'); ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon transactions">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-info">
                    <h3>Jumlah Transaksi</h3>
                    <div class="stat-value"><?php echo $total_transactions; ?></div>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-clock"></i> Transaksi Terbaru
                </h2>
                <?php while ($trans = mysqli_fetch_assoc($result_recent)): ?>
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <h4>#<?php echo str_pad($trans['penjualan_id'], 6, '0', STR_PAD_LEFT); ?></h4>
                            <p><?php echo $trans['nama_pelanggan'] ?? 'Pelanggan Umum'; ?> • <?php echo date('d M Y', strtotime($trans['tanggal_penjualan'])); ?></p>
                        </div>
                        <div class="transaction-price">
                            Rp <?php echo number_format($trans['total_harga'], 0, ',', '.'); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-chart-bar"></i> Penjualan 7 Hari Terakhir
                </h2>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Prepare data for chart
        const salesData = <?php echo json_encode($sales_data); ?>;
        const labels = salesData.map(item => {
            const date = new Date(item.tanggal);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        const data = salesData.map(item => parseFloat(item.total));

        // Create chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: data,
                    borderColor: '#ff6a00',
                    backgroundColor: 'rgba(255, 106, 0, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ff6a00',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 14
                        },
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>