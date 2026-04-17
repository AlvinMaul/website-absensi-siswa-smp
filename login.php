<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi SMP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e88e5;
            --secondary-color: #0d47a1;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            max-width: 500px;
            margin-top: 100px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .login-header::before {
            content: "";
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 150px;
            height: 150px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .login-header::after {
            content: "";
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .login-body {
            background-color: white;
            padding: 30px;
        }
        
        .logo-school {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
            border-radius: 50%;
            border: 3px solid white;
            background-color: white;
            padding: 5px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(30, 136, 229, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 136, 229, 0.4);
        }
        
        .input-group-text {
            border-radius: 10px 0 0 10px;
            background-color: #f8f9fa;
        }
        
        .floating-label {
            position: relative;
            margin-bottom: 20px;
        }
        
        .floating-label label {
            position: absolute;
            top: 12px;
            left: 45px;
            color: #999;
            transition: all 0.2s;
            pointer-events: none;
            background: white;
            padding: 0 5px;
        }
        
        .floating-label input:focus ~ label,
        .floating-label input:not(:placeholder-shown) ~ label {
            top: -9px;
            left: 40px;
            font-size: 12px;
            color: var(--primary-color);
            background: linear-gradient(to bottom, rgba(255,255,255,0.9) 50%, rgba(255,255,255,0));
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            width: 16px;
            height: 16px;
            margin-right: 5px;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-container {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 login-container">
                <div class="login-header">
                    <img src="logoschool.jpeg" alt="Logo Sekolah - SMP Negeri dengan latar biru dan tulisan logo" 
                    class="rounded-circle mb-2" 
                    style="width: 120px; height: 120px; object-fit: cover;">
                    <h3>SISTEM ABSENSI SMP</h3>
                    <p>Digitalisasi Presensi Siswa</p>
                </div>
                
                <div class="login-body">
                    <form>
                        <div class="floating-label">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" placeholder="Username">
                            </div>
                            <label for="username">Username</label>
                        </div>
                        
                        <div class="floating-label">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" placeholder="Password">
                            </div>
                            <label for="password">Password</label>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <div class="remember-me">
                                <input type="checkbox" id="remember" checked>
                                <label for="remember">Ingat saya</label>
                            </div>
                            <div>
                                <a href="#lupa-password" style="font-size: 14px;">Lupa password?</a>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i> MASUK
                        </button>
                        
                        <div class="login-footer">
                            <p>Tahun Ajaran 2024/2025 • <a href="#" target="_blank">SMP Jakarta 48</a></p>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Contoh validasi sederhana
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if(username === '' || password === '') {
                alert('Username dan password harus diisi!');
                return;
            }
            
            // Login dengan AJAX bisa ditambahkan di sini
            window.location.href = 'index.php';
        });
    </script>
</body>
</html>
