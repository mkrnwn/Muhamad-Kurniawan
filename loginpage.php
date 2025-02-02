<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register Form</title>

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- Background Video CSS -->
    <style>
        body {
            background-image: url('https://wallpaperaccess.com/full/9340380.jpg');
            background-size: cover;
            /* Menyesuaikan ukuran gambar dengan layar */
            background-position: center;
            /* Menempatkan gambar di tengah */
            background-repeat: no-repeat;
            /* Mencegah gambar berulang */
        }
    </style>
</head>

<body>

    <!-- Content Wrapper -->
    <div class="wrapper">
        <span class="rotate-bg"></span>
        <span class="rotate-bg2"></span>

        <!-- Form Login -->
        <div class="form-box login">
            <h2 class="title animation" style="--i:0; --j:21">Login</h2>
            <form action="login.php" method="POST">
                <div class="input-box animation" style="--i:1; --j:22">
                    <input type="text" name="username" required>
                    <label for="">Username</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box animation" style="--i:2; --j:23">
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn animation" style="--i:3; --j:24">Login</button>
                <div class="linkTxt animation" style="--i:5; --j:25">
                    <p>Don't have an account? <a href="#" class="register-link">Sign Up</a></p>
                </div>
            </form>
        </div>

        <!-- Info Text for Login -->
        <div class="info-text login">
            <h2 class="animation" style="--i:0; --j:20">Welcome Assessor!</h2>
            <p class="animation" style="--i:1; --j:21">Silahkan login atau daftar dulu untuk segera mengakses assessment COBIT5</p>
        </div>

        <!-- Form Register -->
        <div class="form-box register">
            <h2 class="title animation" style="--i:17; --j:0">Sign Up</h2>
            <form action="register.php" method="POST">
                <div class="input-box animation" style="--i:18; --j:1">
                    <input type="text" name="username" required>
                    <label for="">Username</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box animation" style="--i:19; --j:2">
                    <input type="email" name="email" required>
                    <label for="">Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box animation" style="--i:20; --j:3">
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn animation" style="--i:21;--j:4">Sign Up</button>
                <div class="linkTxt animation" style="--i:22; --j:5">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                </div>
            </form>
        </div>

        <div class="info-text register">
            <h2 class="animation" style="--i:17; --j:0;">Welcome Assessor!</h2>
            <p class="animation" style="--i:18; --j:1;">Silahkan login atau daftar dulu untuk segera mengakses assessment COBIT5</p>
        </div>
    </div>

    <!-- Script.js -->
    <script src="script.js"></script>
</body>

</html>