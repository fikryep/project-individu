<h2>Menu Bakso BM</h2>
<div class="menu-grid">
    <?php 
    foreach ($menu_bakso as $item) {
        echo '<div class="menu-item">';
        echo '<img src="' . $item['gambar'] . '" alt="' . $item['nama'] . '">';
        echo '<h3>' . $item['nama'] . '</h3>';
        echo '<p>' . $item['deskripsi'] . '</p>';
        echo '<p class="price">Rp ' . number_format($item['harga'], 0, ',', '.') . '</p>';
        echo '<a href="?page=pesan&id=' . $item['id'] . '" class="btn" style="margin: 0 0 1rem 1rem;">Pesan</a>';
        echo '</div>';
    }
    ?>
</div>