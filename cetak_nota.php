<?php
require_once 'config.php';

$penjualan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($penjualan_id == 0) {
    die("ID penjualan tidak valid");
}

// Get transaction data with COALESCE for pelanggan umum
$query = "SELECT p.*, 
          COALESCE(pel.nama_pelanggan, 'Pelanggan Umum') as nama_pelanggan, 
          pel.nomor_telepon 
          FROM penjualan p 
          LEFT JOIN pelanggan pel ON p.pelanggan_id = pel.pelanggan_id 
          WHERE p.penjualan_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $penjualan_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$penjualan = mysqli_fetch_assoc($result);

if (!$penjualan) {
    die("Data penjualan tidak ditemukan");
}

// Get transaction details
$detail_query = "SELECT dp.*, pr.NamaProduk, pr.Harga 
                 FROM detail_penjualan dp 
                 JOIN produk pr ON dp.produk_id = pr.produk_id
                 WHERE dp.penjualan_id = ?";
$detail_stmt = mysqli_prepare($conn, $detail_query);
mysqli_stmt_bind_param($detail_stmt, "i", $penjualan_id);
mysqli_stmt_execute($detail_stmt);
$detail_result = mysqli_stmt_get_result($detail_stmt);

// Calculate change
$kembalian = $penjualan['jumlah_bayar'] - $penjualan['total_harga'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian - <?php echo $penjualan_id; ?></title>
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
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .struk-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .struk {
            border: 2px dashed #333;
            padding: 20px;
            background: white;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border-radius: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #ff6a00;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #ff6a00;
            font-weight: 700;
        }

        .header p {
            font-size: 12px;
            margin: 2px 0;
            color: #666;
        }

        .info-section {
            margin-bottom: 15px;
            font-size: 12px;
            border-bottom: 1px dashed #333;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .items-section {
            margin-bottom: 15px;
            border-bottom: 2px solid #ff6a00;
            padding-bottom: 10px;
        }

        .item {
            margin: 8px 0;
            font-size: 13px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            margin-top: 3px;
            font-size: 12px;
            color: #666;
        }

        .total-section {
            margin-bottom: 15px;
            font-size: 14px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #ff6a00;
            padding-top: 10px;
            margin-top: 10px;
        }

        .total-row.payment {
            color: #333;
        }

        .total-row.change {
            color: #28a745;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            border-top: 1px dashed #333;
            padding-top: 15px;
            margin-top: 15px;
        }

        .footer p {
            margin: 3px 0;
        }

        .print-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 106, 0, 0.3);
        }

        .print-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 106, 0, 0.4);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            width: 100%;
            margin-top: 20px;
        }

        .back-btn {
            flex: 1;
            padding: 15px;
            background: white;
            color: #ff6a00;
            border: 2px solid #ff6a00;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .back-btn:hover {
            background: rgba(255, 106, 0, 0.1);
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .struk-container {
                box-shadow: none;
            }

            .struk {
                border: none;
                box-shadow: none;
                border-radius: 0;
            }

            .print-btn, .action-buttons, .back-btn {
                display: none;
            }

            .header {
                border-bottom-color: #333;
            }

            .items-section {
                border-bottom-color: #333;
            }

            .total-row.grand-total {
                border-top-color: #333;
            }
        }
    </style>
</head>
<body>
    <div class="struk-container">
        <div class="struk">
            <div class="header">
                <h1><i class="fas fa-cash-register"></i> POS UMKM</h1>
                <p>Jl. Contoh No. 123</p>
                <p>Telp: 081234567890</p>
            </div>

            <div class="info-section">
                <div class="info-row">
                    <span>No. Transaksi:</span>
                    <span><strong>#<?php echo str_pad($penjualan_id, 6, '0', STR_PAD_LEFT); ?></strong></span>
                </div>
                <div class="info-row">
                    <span>Tanggal:</span>
                    <span><?php echo date('d/m/Y H:i', strtotime($penjualan['tanggal_penjualan'])); ?></span>
                </div>
                <div class="info-row">
                    <span>Pelanggan:</span>
                    <span><?php echo htmlspecialchars($penjualan['nama_pelanggan']); ?></span>
                </div>
            </div>

            <div class="items-section">
                <h3 style="margin-bottom: 10px;">Detail Pembelian</h3>
                <?php while ($item = mysqli_fetch_assoc($detail_result)): ?>
                    <div class="item">
                        <div class="item-header">
                            <span><?php echo htmlspecialchars($item['NamaProduk']); ?></span>
                            <span>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="item-details">
                            <span><?php echo $item['jumlah_produk']; ?> x Rp <?php echo number_format($item['Harga'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="total-section">
                <div class="total-row grand-total">
                    <span>TOTAL:</span>
                    <span>Rp <?php echo number_format($penjualan['total_harga'], 0, ',', '.'); ?></span>
                </div>
                
                <div class="total-row payment">
                    <span>TUNAI:</span>
                    <span>Rp <?php echo number_format($penjualan['jumlah_bayar'], 0, ',', '.'); ?></span>
                </div>
                
                <?php if ($kembalian > 0): ?>
                    <div class="total-row change">
                        <span>KEMBALIAN:</span>
                        <span>Rp <?php echo number_format($kembalian, 0, ',', '.'); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="footer">
                <p><strong>Terima Kasih Atas Kunjungan Anda!</strong></p>
                <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
                <p>Simpan struk ini sebagai bukti pembelian</p>
            </div>
        </div>

        <button class="print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Struk
        </button>
        
        <div class="action-buttons">
            <a href="javascript:history.back()" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</body>
</html>
