<?php
// Cari menu berdasarkan ID
$selected_menu = null;
if (isset($_GET['id'])) {
    $menu_id = intval($_GET['id']);
    foreach ($menu_bakso as $menu) {
        if ($menu['id'] == $menu_id) {
            $selected_menu = $menu;
            break;
        }
    }
}
?>

<h2>Form Pemesanan</h2>

<?php if ($selected_menu): ?>
<div class="order-form">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h3>Anda memesan:</h3>
        <p><strong><?php echo $selected_menu['nama']; ?></strong></p>
        <p>Rp <?php echo number_format($selected_menu['harga'], 0, ',', '.'); ?></p>
    </div>

    <form method="post">
        <input type="hidden" name="menu_id" value="<?php echo $selected_menu['id']; ?>">
        
        <div class="form-group">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        
        <div class="form-group">
            <label for="no_hp">Nomor HP:</label>
            <input type="tel" id="no_hp" name="no_hp" required>
        </div>
        
        <div class="form-group">
            <label for="alamat">Alamat Pengiriman:</label>
            <textarea id="alamat" name="alamat" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="quantity">Jumlah Pesan:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" required>
        </div>
        
        <button type="submit" name="pesan" class="btn">Pesan Sekarang</button>
    </form>
</div>
<?php else: ?>
    <div style="text-align: center;">
        <p>Menu tidak ditemukan. Silakan pilih menu terlebih dahulu.</p>
        <a href="?page=menu" class="btn">Lihat Menu</a>
    </div>
<?php endif; ?>