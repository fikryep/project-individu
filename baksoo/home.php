<section class="hero">
    <div class="hero-content">
        <p class="owner-name">Owner: Fikry</p>
        <h2>Selamat Datang di Bakso BM</h2>
        <p>Nikmati kelezatan bakso dengan resep turun temurun sejak 1995</p>
        <a href="?page=pesan" class="btn btn-primary">Pesan Sekarang</a>
    </div>
</section>

<div class="home-container">
    <!-- Profil Sederhana -->
    <section class="profile-section">
        <div class="profile-card">
            <h3><i class="fas fa-store"></i> Profil Kami</h3>
            <div class="profile-content">
                <img src="images/profil-bakso.jpg" alt="Profil Bakso Mantap" class="profile-img">
                <div class="profile-text">
                    <p>Bakso BM telah menjadi favorit warga sejak 1995. Kami menggunakan daging sapi pilihan dan rempah-rempah asli untuk menciptakan cita rasa yang tak terlupakan.</p>
                    <div class="profile-highlight">
                        <p><i class="fas fa-check"></i> 100% Daging Sapi</p>
                        <p><i class="fas fa-check"></i> Bumbu Racikan Sendiri</p>
                        <p><i class="fas fa-check"></i> Higienis dan Terjamin</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jam Buka -->
    <section class="opening-hours">
        <div class="hours-card">
            <h3><i class="fas fa-clock"></i> Jam Buka</h3>
            <ul class="hours-list">
                <li><span>Senin-Jumat</span> <span>09:00 - 21:00</span></li>
                <li><span>Sabtu-Minggu</span> <span>08:00 - 22:00</span></li>
                <li><span>Hari Libur</span> <span>10:00 - 20:00</span></li>
            </ul>
            <div class="cta-box">
                <p>Ingin pesan diluar jam buka? Hubungi kami!</p>
                <a href="tel:+6285813029151" class="btn btn-outline">
                    <i class="fas fa-phone"></i> 085813029151
                </a>
            </div>
        </div>
    </section>

    <!-- Menu Spesial -->
    <section class="special-menu">
        <h3>Menu Spesial Hari Ini</h3>
        <div class="menu-grid">
            <?php 
            for ($i = 0; $i < min(3, count($menu_bakso)); $i++) {
                $item = $menu_bakso[$i];
                echo '<div class="menu-item">';
                if ($item['id'] >= 4) {
                    echo '<span class="new-badge">NEW</span>';
                }
                echo '<img src="' . $item['gambar'] . '" alt="' . $item['nama'] . '">';
                echo '<h3>' . $item['nama'] . '</h3>';
                echo '<p>' . $item['deskripsi'] . '</p>';
                echo '<p class="price">Rp ' . number_format($item['harga'], 0, ',', '.') . '</p>';
                echo '<a href="?page=pesan&id=' . $item['id'] . '" class="btn">Pesan</a>';
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <!-- Riwayat Pesanan -->
    <section class="order-history">
        <h3>Pesanan Terbaru</h3>
        <div class="history-container">
            <table class="history-table">
                <!-- ... (kode riwayat pesanan sebelumnya) ... -->
            </table>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="cta-content">
            <h3>Siap Menikmati Bakso Terlezat?</h3>
            <p>Pesan sekarang dan dapatkan promo khusus pembeli pertama!</p>
            <div class="cta-buttons">
                <a href="?page=menu" class="btn btn-primary">Lihat Menu Lengkap</a>
                <a href="https://wa.me/6285813029151" class="btn btn-whatsapp">
                    <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
                </a>
            </div>
        </div>
    </section>
    
    <section class="order-history">
    <h3>Riwayat Pesanan Terakhir</h3>
    <div class="history-container">
        <table class="history-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Customer</th>
                    <th>Pesanan</th>
                    <th>Total</th>
                    <th>Waktu Pesan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT 
                            @no:=@no+1 AS nomor, 
                            nama, 
                            pesanan, 
                            total_harga, 
                            DATE_FORMAT(tanggal_pesan, '%d/%m/%Y %H:%i') as waktu_pesan 
                          FROM customers, (SELECT @no:=0) AS no 
                          ORDER BY tanggal_pesan DESC 
                          LIMIT 5";
                $result = $conn->query($query);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['nomor'] . '</td>';
                        echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['pesanan']) . '</td>';
                        echo '<td>Rp ' . number_format($row['total_harga'], 0, ',', '.') . '</td>';
                        echo '<td>' . $row['waktu_pesan'] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5" class="no-data">Belum ada riwayat pesanan</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>
</div>