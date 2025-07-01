<?php
$nama = isset($_GET['nama']) ? htmlspecialchars($_GET['nama']) : '';
$pesanan = isset($_GET['pesanan']) ? htmlspecialchars($_GET['pesanan']) : '';
$total = isset($_GET['total']) ? floatval($_GET['total']) : 0;
$antrian = isset($_GET['antrian']) ? intval($_GET['antrian']) : 0;
?>

<div class="thank-you">
    <h2>Terima Kasih, <?php echo $nama; ?>!</h2>
    <p>Pesanan Anda telah kami terima dan akan segera diproses.</p>
    
    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 2rem 0; text-align: left;">
        <h3>Detail Pesanan:</h3>
        <p><strong>Nomor Antrian:</strong> <?php echo $antrian; ?></p>
        <p><strong>Pesanan:</strong> <?php echo $pesanan; ?></p>
        <p><strong>Total Harga:</strong> Rp <?php echo number_format($total, 0, ',', '.'); ?></p>
    </div>
    
    <div style="margin: 2rem 0;">
        <h3>Informasi Pengiriman:</h3>
        <p>Pesanan Anda akan dikirim dalam waktu 30-60 menit.</p>
        <p>Nomor antrian Anda: <strong style="font-size: 1.5rem;"><?php echo $antrian; ?></strong></p>
        <p>Jika ada pertanyaan, silakan hubungi kami di 0812-3456-7890.</p>
    </div>
    
    <a href="?page=home" class="btn">Kembali ke Beranda</a>
</div>