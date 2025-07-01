<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ?page=login");
    exit();
}

// Tangani penghapusan pesanan
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = intval($_GET['id']);
    $delete = $conn->prepare("DELETE FROM customers WHERE id = ?");
    $delete->bind_param("i", $id);
    $delete->execute();
    header("Location: ?page=admin&view=orders");
    exit();
}

// Tangani penyimpanan perubahan menu
if (isset($_POST['simpan_menu']) && isset($_POST['menu'])) {
    foreach ($_POST['menu'] as $menu) {
        $id = intval($menu['id']);
        $nama = mysqli_real_escape_string($conn, $menu['nama']);
        $deskripsi = mysqli_real_escape_string($conn, $menu['deskripsi']);
        $harga = intval($menu['harga']);

        $update = $conn->prepare("UPDATE menu_bakso SET nama=?, deskripsi=?, harga=? WHERE id=?");
        $update->bind_param("ssii", $nama, $deskripsi, $harga, $id);
        $update->execute();
    }
    echo "<script>alert('Menu berhasil diperbarui'); window.location.href='?page=admin&view=menu';</script>";
    exit();
}

// Default view
$view = isset($_GET['view']) ? $_GET['view'] : '';
?>

<section class="admin-panel">
    <h2><i class="fas fa-cog"></i> Admin Panel</h2>
    <div class="admin-actions">
        <a href="?page=admin&view=orders" class="btn">Kelola Pesanan</a>
        <a href="?page=admin&view=menu" class="btn">Kelola Menu</a>
    </div>

    <?php if ($view === 'orders'): ?>
        <div class="order-list">
            <h3>Daftar Pesanan</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Pesanan</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM customers ORDER BY tanggal_pesan DESC";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['pesanan']) ?></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <a href="?page=admin&action=delete&id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus pesanan ini?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php elseif ($view === 'menu'): ?>
        <div class="menu-list">
            <h3>Kelola Menu Bakso</h3>
            <form method="post" action="?page=admin&view=menu">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Harga (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM menu_bakso ORDER BY id ASC";
                        $result = mysqli_query($conn, $query);
                        while ($menu = mysqli_fetch_assoc($result)):
                        ?>
                            <tr>
                                <td>
                                    <input type="text" name="menu[<?= $menu['id'] ?>][nama]" value="<?= htmlspecialchars($menu['nama']) ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="menu[<?= $menu['id'] ?>][deskripsi]" value="<?= htmlspecialchars($menu['deskripsi']) ?>" required>
                                </td>
                                <td>
                                    <input type="number" name="menu[<?= $menu['id'] ?>][harga]" value="<?= $menu['harga'] ?>" required>
                                </td>
                                <input type="hidden" name="menu[<?= $menu['id'] ?>][id]" value="<?= $menu['id'] ?>">
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <button type="submit" name="simpan_menu" class="btn">Simpan Perubahan</button>
            </form>
        </div>
    <?php endif; ?>
</section>
