<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Kas Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .login-card h5 {
            color: #667eea;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card border-0 shadow-lg p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-wallet2" style="font-size: 3rem; color: #667eea;"></i>
                        </div>
                        <h5 class="mb-1">Login Kas Kelas</h5>
                        <p class="text-muted small">Kelola keuangan kelas dengan transparan</p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="actions/proses_login.php">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase">NISN / NIK</label>
                            <input
                                type="text"
                                name="nisn_nik"
                                class="form-control form-control-lg"
                                placeholder="Masukkan NISN atau NIK Anda"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control form-control-lg"
                                placeholder="Masukkan password Anda"
                                required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>
                    </form>

                    <hr class="my-4">

                    <!-- Info -->
                    <div class="alert alert-info alert-sm rounded-2 mb-0" style="font-size: 0.85rem;">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Hubungi Bendahara atau Wali Kelas</strong> jika Anda belum memiliki akun.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>