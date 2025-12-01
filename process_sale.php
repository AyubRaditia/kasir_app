<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

 $pelanggan_id = intval($_POST['pelanggan_id']);
 $total_harga = floatval($_POST['total_harga']);
 $jumlah_bayar = floatval($_POST['jumlah_bayar']);
 $cart = json_decode($_POST['cart'], true);

if (empty($cart)) {
    echo json_encode(['success' => false, 'message' => 'Keranjang kosong']);
    exit;
}

if ($jumlah_bayar < $total_harga) {
    echo json_encode(['success' => false, 'message' => 'Jumlah bayar kurang']);
    exit;
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Insert into penjualan table with payment amount
    $tanggal = date('Y-m-d H:i:s');
    $query = "INSERT INTO penjualan (tanggal_penjualan, total_harga, pelanggan_id, jumlah_bayar) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sdid", $tanggal, $total_harga, $pelanggan_id, $jumlah_bayar);
    mysqli_stmt_execute($stmt);
    
    $penjualan_id = mysqli_insert_id($conn);
    
    // Insert into detail_penjualan and update stock
    foreach ($cart as $item) {
        $produk_id = $item['ProdukID'];
        $jumlah = $item['quantity'];
        $subtotal = $item['Harga'] * $jumlah;
        
        // Check stock availability
        $check_query = "SELECT Stok FROM produk WHERE ProdukID = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "i", $produk_id);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        $product = mysqli_fetch_assoc($result);
        
        if (!$product || $product['Stok'] < $jumlah) {
            throw new Exception("Stok tidak mencukupi untuk produk ID: " . $produk_id);
        }
        
        // Insert detail
        $detail_query = "INSERT INTO detail_penjualan (penjualan_id, produk_id, jumlah_produk, subtotal) VALUES (?, ?, ?, ?)";
        $detail_stmt = mysqli_prepare($conn, $detail_query);
        mysqli_stmt_bind_param($detail_stmt, "iiid", $penjualan_id, $produk_id, $jumlah, $subtotal);
        mysqli_stmt_execute($detail_stmt);
        
        // Update stock
        $update_query = "UPDATE produk SET Stok = Stok - ? WHERE ProdukID = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $jumlah, $produk_id);
        mysqli_stmt_execute($update_stmt);
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Calculate change
    $kembalian = $jumlah_bayar - $total_harga;
    
    echo json_encode([
        'success' => true,
        'penjualan_id' => $penjualan_id,
        'kembalian' => $kembalian
    ]);
    
} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
