<?php ob_start();?>
<?php
session_start();
echo "<script>console.log('Session:', " . json_encode($_SESSION) . ")</script>";
echo "<script>console.log('Session Data:', ".json_encode($_SESSION).")</script>";
echo password_hash("admin123", PASSWORD_DEFAULT);
require 'config.php';

// Koneksi Database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_bakso";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// PROTECT ADMIN PAGES
$admin_pages = ['admin'];
if (isset($_GET['page']) && in_array($_GET['page'], $admin_pages)) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: ?page=home");
        exit();
    }
}

// PROTECT ORDER PAGE
if (isset($_GET['page']) && $_GET['page'] === 'pesan' && !isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

// Data Menu Bakso
$menu_bakso = [
    [
        'id' => 1,
        'nama' => 'Bakso urat',
        'deskripsi' => 'Bakso daging sapi dengan kuah kaldu spesial',
        'harga' => 15000,
        'gambar' => 'urat.jpg'
    ],
    [
        'id' => 2,
        'nama' => 'Bakso jumbo',
        'deskripsi' => 'Bakso ukuran besar dengan isi daging lebih banyak',
        'harga' => 20000,
        'gambar' => 'jumbo.jpg'
    ],
    [
        'id' => 3,
        'nama' => 'Bakso telor',
        'deskripsi' => 'Bakso dengan campuran daging sapi dan udang',
        'harga' => 18000,
        'gambar' => 'telor.jpg'
    ],
    [
        'id' => 4,
        'nama' => 'Bakso keju',
        'deskripsi' => 'Bakso dengan isi keju mozarella yang lumer',
        'harga' => 22000,
        'gambar' => 'keju.jpg'
    ],
    [
        'id' => 5,
        'nama' => 'Bakso beranak',
        'deskripsi' => 'Bakso jumbo berisi bakso-bakso kecil di dalamnya',
        'harga' => 28000,
        'gambar' => 'beranak.jpg'
    ]
];

// Proses Pemesanan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pesan'])) {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $menu_id = $_POST['menu_id'];
    $quantity = intval($_POST['quantity']);
    
    // Cari menu yang dipesan
    $menu_pesanan = null;
    foreach ($menu_bakso as $menu) {
        if ($menu['id'] == $menu_id) {
            $menu_pesanan = $menu;
            break;
        }
    }
    
    if ($menu_pesanan) {
        $total_harga = $menu_pesanan['harga'] * $quantity;
        $detail_pesanan = $quantity . 'x ' . $menu_pesanan['nama'] . ' (@Rp ' . number_format($menu_pesanan['harga'], 0, ',', '.') . ')';
        
        // Dapatkan nomor antrian terakhir
        $result = $conn->query("SELECT MAX(no_antrian) as last_queue FROM customers");
        $row = $result->fetch_assoc();
        $no_antrian = $row['last_queue'] ? $row['last_queue'] + 1 : 1;
        
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO customers (no_antrian, nama, no_hp, alamat, pesanan, total_harga) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssd", $no_antrian, $nama, $no_hp, $alamat, $detail_pesanan, $total_harga);
        
        if ($stmt->execute()) {
            header("Location: ?page=thankyou&nama=" . urlencode($nama) . "&pesanan=" . urlencode($detail_pesanan) . "&total=" . $total_harga . "&antrian=" . $no_antrian);
            exit();
        } else {
            $error = "Terjadi kesalahan saat menyimpan pesanan. Silakan coba lagi.";
        }

        if(isset($_SESSION['user'])) {
            $user_id = $_SESSION['user']['id'];
            $conn->query("UPDATE customers SET user_id = $user_id WHERE id = {$conn->insert_id}");
        }
    }

    // DEFAULT ROUTING
if (!isset($_GET['page'])) {
    if (isset($_SESSION['user'])) {
        $page = 'home';
    } else {
        $page = 'login';
    }
} else {
    $page = $_GET['page'];
}
}

// Tentukan halaman yang akan ditampilkan
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakso Mantap</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }
        
        header {
            background-color: #e74c3c;
            color: white;
            padding: 2rem 0;
            text-align: center;
        }
        
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        nav {
            background-color: #333;
            display: flex;
            justify-content: center;
            padding: 1rem 0;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1.5rem;
            margin: 0 0.5rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        nav a:hover {
            background-color: #e74c3c;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            min-height: 60vh;
        }
        
        .btn {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 0.7rem 1.5rem;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn:hover {
            background-color: #c0392b;
        }
        
        /* Home Page */
        .hero {
            text-align: center;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .menu-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .menu-item:hover {
            transform: translateY(-5px);
        }
        
        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .menu-item h3 {
            padding: 1rem 1rem 0;
        }
        
        .menu-item p {
            padding: 0 1rem;
            color: #666;
            margin: 0.5rem 0;
        }
        
        .price {
            font-weight: bold;
            color: #e74c3c;
            font-size: 1.1rem;
            padding: 0 1rem 1rem !important;
        }
        
        /* Form Pemesanan */
        .order-form {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        /* Thank You Page */
        .thank-you {
            text-align: center;
            background: white;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 2rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .alert.error {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
    </style>
</head>
<body>
    <header>
        <nav>
    <a href="?page=home">Home</a>
    <a href="?page=menu">Menu</a>
    <?php if(isset($_SESSION['user'])): ?>
        <?php if($_SESSION['user']['role'] === 'admin'): ?>
            <a href="?page=admin" class="btn">Admin</a>
        <?php endif; ?>
        <a href="?page=logout" class="btn">Logout</a>
    <?php else: ?>
        <a href="?page=login" class="btn">Login</a>
    <?php endif; ?>
</nav>
        <h1>Bakso BM</h1>
        <div class="header-content">
        <div class="company-brand">
            <p class="company-name">PT. LATOM CULTURE</p>
            <p class="owner-info">Owner: <strong>Fikry Assalaf, S.kom.</strong></p>
        </div>
        <p>Nikmati kelezatan bakso dengan resep turun temurun</p>
        <img src="logo.png" alt="logo">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </header>
    
    <nav>
        <a href="?page=home">Home</a>
        <a href="?page=menu">Menu</a>
        <a href="?page=pesan">Pesan</a>
    </nav>
    
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php
switch ($page) {
    case 'home':
        include 'home.php';
        break;
    case 'menu':
        include 'menu.php';
        break;
    case 'pesan':
        include 'pesan.php';
        break;
    case 'thankyou':
        include 'thankyou.php';
        break;
    case 'register':
        include 'register.php';
        break;
    case 'login':
        include 'login.php';
        break;
    case 'logout':
        session_destroy();
        header("Location: ?page=home");
        exit();
        break;
    case 'admin':
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo "<script>alert('Akses ditolak!'); window.location.href='?page=login';</script>";
        exit();
    }
    include 'admin.php';
    break;
    case 'auth':
    require_once 'auth.php';
        if ($_GET['action'] === 'register') {
        if (register($_POST)) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='?page=login';</script>";
            } else {
                echo "<script>alert('Registrasi gagal.'); window.location.href='?page=register';</script>";
            }
        }
        break;
    default:
        // Redirect ke login jika belum login, atau home jika sudah login
        if(!isset($_SESSION['user'])) {
            header("Location: ?page=login");
        } else {
            header("Location: ?page=home");
        }
        exit();
}
?>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Bakso Mantap. All rights reserved.</p>
    </footer>
</body>
</html>