
<section class="auth-form">
    <h2>Daftar Akun Baru</h2>
    <form method="POST" action="?page=auth&action=register">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="?page=login">Login disini</a></p>
</section>