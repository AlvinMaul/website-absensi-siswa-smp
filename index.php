<?php
// Koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "absensi_siswa";
$conn = mysqli_connect($host, $username, $password, $database);

if(!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

session_start();

// Fungsi untuk mencegah SQL injection
function clean_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
}

// --- Logika PHP untuk Tambah Siswa ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_siswa'])) {
    $nis = clean_input($_POST['nis']);
    $nama_lengkap = clean_input($_POST['nama_lengkap']);
    $tempat_lahir = clean_input($_POST['tempat_lahir']);
    $tanggal_lahir = clean_input($_POST['tanggal_lahir']);
    $jenis_kelamin = clean_input($_POST['jenis_kelamin']);
    $id_kelas = clean_input($_POST['id_kelas']); // Ini akan menjadi ID kelas dari database
    $alamat = clean_input($_POST['alamat']);
    $nama_ayah = clean_input($_POST['nama_ayah']);
    $nama_ibu = clean_input($_POST['nama_ibu']);
    $no_telp_ortu = clean_input($_POST['no_telp_ortu']);

    // Cek apakah NIS sudah ada
    $check_nis_sql = "SELECT nis FROM siswa WHERE nis = '$nis'";
    $check_nis_result = mysqli_query($conn, $check_nis_sql);
    if (mysqli_num_rows($check_nis_result) > 0) {
        echo "<script>alert('NIS sudah terdaftar. Mohon gunakan NIS lain.');</script>";
    } else {
        $sql = "INSERT INTO siswa (nis, nama_siswa, tempat_lahir, tanggal_lahir, jenis_kelamin, id_kelas, alamat, nama_ayah, nama_ibu, no_telp_ortu)
                VALUES ('$nis', '$nama_lengkap', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$id_kelas', '$alamat', '$nama_ayah', '$nama_ibu', '$no_telp_ortu')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Data siswa berhasil ditambahkan!'); window.location.href = '#siswa';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// --- Logika PHP untuk Simpan Absensi ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_absensi'])) {
    $tanggal_absensi = clean_input($_POST['tanggal_absensi']);
    $id_kelas_absensi = clean_input($_POST['id_kelas_absensi']);
    $id_mapel_absensi = clean_input($_POST['id_mapel_absensi']);
    $id_guru_absensi = clean_input($_POST['id_guru_absensi']);
    $admin_id = 1; // Asumsi ID admin yang sedang login adalah 1

    // Ambil data siswa dari form
    foreach ($_POST['siswa_id'] as $index => $siswa_id) {
        $siswa_id = clean_input($siswa_id);
        $status_absensi = clean_input($_POST['status_' . $siswa_id]); // Menggunakan nama status yang unik per siswa
        $keterangan = clean_input($_POST['keterangan_' . $siswa_id]);

        // Dapatkan id_jenis_absensi berdasarkan kode_absensi (H, I, S, A)
        $get_jenis_absensi_sql = "SELECT id_jenis_absensi FROM jenis_absensi WHERE kode_absensi = '$status_absensi'";
        $get_jenis_absensi_result = mysqli_query($conn, $get_jenis_absensi_sql);
        $jenis_absensi_row = mysqli_fetch_assoc($get_jenis_absensi_result);
        $id_jenis_absensi = $jenis_absensi_row['id_jenis_absensi'];

        // Cek apakah absensi untuk siswa, tanggal, mapel, dan guru ini sudah ada
        $check_absensi_sql = "SELECT id_absensi FROM absensi WHERE id_siswa = '$siswa_id' AND tanggal = '$tanggal_absensi' AND id_mapel = '$id_mapel_absensi' AND id_guru = '$id_guru_absensi'";
        $check_absensi_result = mysqli_query($conn, $check_absensi_sql);

        if (mysqli_num_rows($check_absensi_result) > 0) {
            // Update absensi jika sudah ada
            $update_absensi_sql = "UPDATE absensi SET id_jenis_absensi = '$id_jenis_absensi', keterangan = '$keterangan', waktu_input = NOW() WHERE id_siswa = '$siswa_id' AND tanggal = '$tanggal_absensi' AND id_mapel = '$id_mapel_absensi' AND id_guru = '$id_guru_absensi'";
            mysqli_query($conn, $update_absensi_sql);
        } else {
            // Insert absensi baru
            $insert_absensi_sql = "INSERT INTO absensi (id_siswa, id_mapel, id_guru, tanggal, id_jenis_absensi, keterangan, waktu_input, id_admin)
                                   VALUES ('$siswa_id', '$id_mapel_absensi', '$id_guru_absensi', '$tanggal_absensi', '$id_jenis_absensi', '$keterangan', NOW(), '$admin_id')";
            mysqli_query($conn, $insert_absensi_sql);
        }
    }
    echo "<script>alert('Absensi berhasil disimpan!'); window.location.href = '#absensi';</script>";
}

// --- Ambil Data untuk Dashboard ---
$total_siswa_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM siswa");
$total_siswa = mysqli_fetch_assoc($total_siswa_query)['total'];

$today = date('Y-m-d');
$hadir_hari_ini_query = mysqli_query($conn, "SELECT COUNT(DISTINCT id_siswa) AS total FROM absensi WHERE tanggal = '$today' AND id_jenis_absensi = (SELECT id_jenis_absensi FROM jenis_absensi WHERE kode_absensi = 'H')");
$hadir_hari_ini = mysqli_fetch_assoc($hadir_hari_ini_query)['total'];

$izin_hari_ini_query = mysqli_query($conn, "SELECT COUNT(DISTINCT id_siswa) AS total FROM absensi WHERE tanggal = '$today' AND id_jenis_absensi = (SELECT id_jenis_absensi FROM jenis_absensi WHERE kode_absensi = 'I')");
$izin_hari_ini = mysqli_fetch_assoc($izin_hari_ini_query)['total'];

$alpa_hari_ini_query = mysqli_query($conn, "SELECT COUNT(DISTINCT id_siswa) AS total FROM absensi WHERE tanggal = '$today' AND id_jenis_absensi = (SELECT id_jenis_absensi FROM jenis_absensi WHERE kode_absensi = 'A')");
$alpa_hari_ini = mysqli_fetch_assoc($alpa_hari_ini_query)['total'];

// Ambil data absensi terakhir untuk dashboard
$absensi_terakhir_query = mysqli_query($conn, "SELECT a.tanggal, s.nama_siswa, k.nama_kelas, ja.nama_absensi, ja.warna
                                                FROM absensi a
                                                JOIN siswa s ON a.id_siswa = s.id_siswa
                                                JOIN kelas k ON s.id_kelas = k.id_kelas
                                                JOIN jenis_absensi ja ON a.id_jenis_absensi = ja.id_jenis_absensi
                                                ORDER BY a.waktu_input DESC LIMIT 3");
$absensi_terakhir = [];
while ($row = mysqli_fetch_assoc($absensi_terakhir_query)) {
    $absensi_terakhir[] = $row;
}

// Ambil data siswa untuk tabel Data Siswa
$data_siswa_query = mysqli_query($conn, "SELECT s.nis, s.nama_siswa, k.nama_kelas, s.jenis_kelamin, s.alamat, s.no_telp_ortu
                                        FROM siswa s JOIN kelas k ON s.id_kelas = k.id_kelas");
$data_siswa = [];
while ($row = mysqli_fetch_assoc($data_siswa_query)) {
    $data_siswa[] = $row;
}

// Ambil data guru untuk tabel Data Guru
$data_guru_query = mysqli_query($conn, "SELECT g.nip, g.nama_guru, g.jenis_kelamin, mp.nama_mapel, g.no_telp
                                        FROM guru g LEFT JOIN guru_mapel gm ON g.id_guru = gm.id_guru LEFT JOIN mata_pelajaran mp ON gm.id_mapel = mp.id_mapel GROUP BY g.id_guru");
$data_guru = [];
while ($row = mysqli_fetch_assoc($data_guru_query)) {
    $data_guru[] = $row;
}

// Ambil data kelas untuk tampilan Kelas
$data_kelas_query = mysqli_query($conn, "SELECT k.nama_kelas, g.nama_guru AS wali_kelas, (SELECT COUNT(*) FROM siswa WHERE id_kelas = k.id_kelas) AS jumlah_siswa
                                        FROM kelas k LEFT JOIN guru g ON k.id_wali_kelas = g.id_guru");
$data_kelas = [];
while ($row = mysqli_fetch_assoc($data_kelas_query)) {
    $data_kelas[] = $row;
}

// Ambil data untuk dropdown Kelas, Mapel, Guru
$kelas_dropdown_query = mysqli_query($conn, "SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas");
$mapel_dropdown_query = mysqli_query($conn, "SELECT id_mapel, nama_mapel FROM mata_pelajaran ORDER BY nama_mapel");
$guru_dropdown_query = mysqli_query($conn, "SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi SMP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e88e5, #0d47a1);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        .card-header {
            background-color: #1e88e5;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .dataTables_wrapper {
            padding: 10px;
        }
        .badge {
            padding: 8px;
            font-weight: 500;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #1e88e5;
            border-color: #1e88e5;
        }
        .btn-primary:hover {
            background-color: #0d47a1;
            border-color: #0d47a1;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-primary">
                <div class="text-center py-4">
                <img src="logoschool.jpeg" 
                    alt="Logo Sekolah - SMP Negeri dengan latar biru dan tulisan logo" 
                    class="rounded-circle mb-2" 
                    style="width: 120px; height: 120px; object-fit: cover;">
                    
                    <h4>SMP NEGERI</h4>
                    <p class="text-white-50">Sistem Absensi Digital</p>
                </div>
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#absensi" data-bs-toggle="tab">
                                <i class="fas fa-clipboard-check"></i> Absensi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#siswa" data-bs-toggle="tab">
                                <i class="fas fa-users"></i> Data Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#guru" data-bs-toggle="tab">
                                <i class="fas fa-chalkboard-teacher"></i> Data Guru
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#kelas" data-bs-toggle="tab">
                                <i class="fas fa-school"></i> Kelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#laporan" data-bs-toggle="tab">
                                <i class="fas fa-file-alt"></i> Laporan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#pengaturan" data-bs-toggle="tab">
                                <i class="fas fa-cog"></i> Pengaturan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Sistem Absensi Siswa</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> Admin
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#profile">Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#logout">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="tab-content">
                    <!-- Dashboard -->
                    <div class="tab-pane fade show active" id="dashboard">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title">Siswa</h5>
                                                <h2 class="card-text"><?php echo $total_siswa; ?></h2>
                                            </div>
                                            <i class="fas fa-users fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-success mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title">Hadir Hari Ini</h5>
                                                <h2 class="card-text"><?php echo $hadir_hari_ini; ?></h2>
                                            </div>
                                            <i class="fas fa-check-circle fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-warning mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title">Izin</h5>
                                                <h2 class="card-text"><?php echo $izin_hari_ini; ?></h2>
                                            </div>
                                            <i class="fas fa-envelope fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-danger mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title">Alpa</h5>
                                                <h2 class="card-text"><?php echo $alpa_hari_ini; ?></h2>
                                            </div>
                                            <i class="fas fa-times-circle fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Statistik Absensi Bulan Ini</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="attendanceChart" height="150"></canvas>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Absensi Terakhir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nama</th>
                                                        <th>Kelas</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($absensi_terakhir)): ?>
                                                        <?php foreach ($absensi_terakhir as $absensi): ?>
                                                            <tr>
                                                                <td><?php echo date('d-m-Y', strtotime($absensi['tanggal'])); ?></td>
                                                                <td><?php echo $absensi['nama_siswa']; ?></td>
                                                                <td><?php echo $absensi['nama_kelas']; ?></td>
                                                                <td><span class="badge bg-<?php echo $absensi['warna']; ?>"><?php echo $absensi['nama_absensi']; ?></span></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center">Belum ada data absensi.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Kalender</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Absensi -->
                    <div class="tab-pane fade" id="absensi">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Input Absensi</h5>
                            </div>
                            <div class="card-body">
                                <form id="formAbsensi" method="POST" action="">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" name="tanggal_absensi" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Kelas</label>
                                            <select class="form-select" name="id_kelas_absensi" id="selectKelasAbsensi" required>
                                                <option value="">Pilih Kelas</option>
                                                <?php
                                                mysqli_data_seek($kelas_dropdown_query, 0); // Reset pointer
                                                while ($kelas = mysqli_fetch_assoc($kelas_dropdown_query)): ?>
                                                    <option value="<?php echo $kelas['id_kelas']; ?>"><?php echo $kelas['nama_kelas']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Mata Pelajaran</label>
                                            <select class="form-select" name="id_mapel_absensi" required>
                                                <option value="">Pilih Mapel</option>
                                                <?php
                                                mysqli_data_seek($mapel_dropdown_query, 0); // Reset pointer
                                                while ($mapel = mysqli_fetch_assoc($mapel_dropdown_query)): ?>
                                                    <option value="<?php echo $mapel['id_mapel']; ?>"><?php echo $mapel['nama_mapel']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Guru</label>
                                            <select class="form-select" name="id_guru_absensi" required>
                                                <option value="">Pilih Guru</option>
                                                <?php
                                                mysqli_data_seek($guru_dropdown_query, 0); // Reset pointer
                                                while ($guru = mysqli_fetch_assoc($guru_dropdown_query)): ?>
                                                    <option value="<?php echo $guru['id_guru']; ?>"><?php echo $guru['nama_guru']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button type="button" class="btn btn-info" id="loadSiswaBtn"><i class="fas fa-sync-alt"></i> Muat Data Siswa</button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="attendanceTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIS</th>
                                                    <th>Nama Siswa</th>
                                                    <th>Status</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="siswaAbsensiBody">
                                                <!-- Data siswa akan dimuat di sini melalui AJAX -->
                                                <tr>
                                                    <td colspan="5" class="text-center">Pilih kelas dan klik "Muat Data Siswa"</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-end mt-3">
                                        <button type="submit" name="simpan_absensi" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Absensi</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Rekap Absensi</h5>
                                    <div class="d-flex">
                                        <input type="month" class="form-control me-2" id="filterMonth" value="<?php echo date('Y-m'); ?>" style="width: 200px;">
                                        <button class="btn btn-secondary me-2" id="filterRekapBtn"><i class="fas fa-filter"></i> Filter</button>
                                        <button class="btn btn-success"><i class="fas fa-file-excel"></i> Export</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered datatable" id="rekapAbsensiTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th rowspan="2">NIS</th>
                                                <th rowspan="2">Nama</th>
                                                <th rowspan="2">Kelas</th>
                                                <th colspan="3" class="text-center">Jumlah</th>
                                                <th rowspan="2">Persentase</th>
                                            </tr>
                                            <tr>
                                                <th>Hadir</th>
                                                <th>Izin/Sakit</th>
                                                <th>Alpa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data rekap absensi akan dimuat di sini melalui AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Siswa -->
                    <div class="tab-pane fade" id="siswa">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Data Siswa</h5>
                                    <div>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal">
                                            <i class="fas fa-plus"></i> Tambah Siswa
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped datatable" id="dataSiswaTable">
                                        <thead>
                                            <tr>
                                                <th>NIS</th>
                                                <th>Nama</th>
                                                <th>Kelas</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Alamat</th>
                                                <th>No. Telp Orang Tua</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data_siswa as $siswa): ?>
                                                <tr>
                                                    <td><?php echo $siswa['nis']; ?></td>
                                                    <td><?php echo $siswa['nama_siswa']; ?></td>
                                                    <td><?php echo $siswa['nama_kelas']; ?></td>
                                                    <td><?php echo $siswa['jenis_kelamin']; ?></td>
                                                    <td><?php echo $siswa['alamat']; ?></td>
                                                    <td><?php echo $siswa['no_telp_ortu']; ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Guru -->
                    <div class="tab-pane fade" id="guru">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Data Guru</h5>
                                    <div>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahGuruModal">
                                            <i class="fas fa-plus"></i> Tambah Guru
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped datatable" id="dataGuruTable">
                                        <thead>
                                            <tr>
                                                <th>NIP</th>
                                                <th>Nama</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Mata Pelajaran</th>
                                                <th>No. Telp</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data_guru as $guru): ?>
                                                <tr>
                                                    <td><?php echo $guru['nip']; ?></td>
                                                    <td><?php echo $guru['nama_guru']; ?></td>
                                                    <td><?php echo $guru['jenis_kelamin']; ?></td>
                                                    <td><?php echo $guru['nama_mapel'] ?? '-'; ?></td>
                                                    <td><?php echo $guru['no_telp']; ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kelas -->
                    <div class="tab-pane fade" id="kelas">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Data Kelas</h5>
                                    <div>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahKelasModal">
                                            <i class="fas fa-plus"></i> Tambah Kelas
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    $card_colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
                                    $color_index = 0;
                                    foreach ($data_kelas as $kelas):
                                        $current_color = $card_colors[$color_index % count($card_colors)];
                                        $color_index++;
                                    ?>
                                        <div class="col-md-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header bg-<?php echo $current_color; ?> text-white">
                                                    <h5 class="card-title mb-0"><?php echo $kelas['nama_kelas']; ?></h5>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Wali Kelas:</strong> <?php echo $kelas['wali_kelas'] ?? 'Belum Ditentukan'; ?></p>
                                                    <p><strong>Jumlah Siswa:</strong> <?php echo $kelas['jumlah_siswa']; ?></p>
                                                    <a href="#" class="btn btn-sm btn-<?php echo $current_color; ?>">Lihat Detail</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Laporan -->
                    <div class="tab-pane fade" id="laporan">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Cetak Laporan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form>
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Laporan</label>
                                                <select class="form-select">
                                                    <option>Rekap Absensi Siswa</option>
                                                    <option>Rekap Absensi per Kelas</option>
                                                    <option>Rekap Absensi Bulanan</option>
                                                    <option>Rekap Absensi Semester</option>
                                                    <option>Daftar Siswa</option>
                                                    <option>Daftar Guru</option>
                                                </select>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Dari Tanggal</label>
                                                    <input type="date" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Sampai Tanggal</label>
                                                    <input type="date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kelas (opsional)</label>
                                                <select class="form-select">
                                                    <option value="">Semua Kelas</option>
                                                    <?php
                                                    mysqli_data_seek($kelas_dropdown_query, 0); // Reset pointer
                                                    while ($kelas = mysqli_fetch_assoc($kelas_dropdown_query)): ?>
                                                        <option value="<?php echo $kelas['id_kelas']; ?>"><?php echo $kelas['nama_kelas']; ?></option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Cetak PDF</button>
                                            <button type="submit" class="btn btn-success ms-2"><i class="fas fa-file-excel"></i> Export Excel</button>
                                        </form>
                                    </div>
                                    <div class="col-md-6 text-center">
                                    <img src="https://placehold.co/400x300" alt="Ilustrasi laporan absensi dengan grafik, tabel data, dan komputer" class="img-fluid rounded">
                                        <p class="mt-2 text-muted">Pilih parameter laporan untuk mencetak atau mengekspor data</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengaturan -->
                    <div class="tab-pane fade" id="pengaturan">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Profil Pengguna</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                    <img src="https://placehold.co/200x200" alt="Foto profil admin dengan latar belakang biru dan pakaian formal" class="profile-img mb-3">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-primary"><i class="fas fa-camera me-2"></i>Ubah Foto</button>
                                            <button class="btn btn-outline-danger"><i class="fas fa-trash me-2"></i>Hapus Foto</button>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <form>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama Lengkap</label>
                                                    <input type="text" class="form-control" value="Administrator">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Username</label>
                                                    <input type="text" class="form-control" value="admin">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" value="admin@smpsaya.sch.id">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">No. Telepon</label>
                                                    <input type="tel" class="form-control" value="081234567890">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Alamat</label>
                                                <textarea class="form-control" rows="2">Jl. Sekolah No. 123, Kota Bandung</textarea>
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>

                                        <hr class="my-4">

                                        <h5 class="mb-3">Ubah Password</h5>
                                        <form>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Password Saat Ini</label>
                                                    <input type="password" class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Password Baru</label>
                                                    <input type="password" class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Ulangi Password Baru</label>
                                                    <input type="password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 mt-4 border-top">
                    <div class="col-md-6 d-flex align-items-center">
                        <span class="text-muted">© 2025 SMP Jakarta 48 - Sistem Absensi Digital</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Siswa -->
    <div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">NIS</label>
                                <input type="text" class="form-control" name="nis" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_lengkap" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tempat_lahir">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <select class="form-select" name="id_kelas" required>
                                    <option value="">Pilih Kelas</option>
                                    <?php
                                    mysqli_data_seek($kelas_dropdown_query, 0); // Reset pointer
                                    while ($kelas = mysqli_fetch_assoc($kelas_dropdown_query)): ?>
                                        <option value="<?php echo $kelas['id_kelas']; ?>"><?php echo $kelas['nama_kelas']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" rows="3" name="alamat"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Ayah</label>
                                <input type="text" class="form-control" name="nama_ayah">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Ibu</label>
                                <input type="text" class="form-control" name="nama_ibu">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon Orang Tua</label>
                            <input type="tel" class="form-control" name="no_telp_ortu">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="tambah_siswa" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Guru -->
    <div class="modal fade" id="tambahGuruModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">NIP</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select">
                                    <option>Laki-laki</option>
                                    <option>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mata Pelajaran</label>
                                <select class="form-select">
                                    <option>Matematika</option>
                                    <option>Bahasa Indonesia</option>
                                    <option>IPA</option>
                                    <option>IPS</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kelas -->
    <div class="modal fade" id="tambahKelasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wali Kelas</label>
                            <select class="form-select">
                                <option>Bapak Ahmad</option>
                                <option>Ibu Siti</option>
                                <option>Bapak Budi</option>
                                <option>Ibu Anita</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" class="form-control" placeholder="2023/2024">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan (opsional)</label>
                            <textarea class="form-control" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable untuk semua tabel dengan class 'datatable'
            $('.datatable').each(function() {
                if ($.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable().destroy();
                }
                $(this).DataTable({
                    responsive: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                    }
                });
            });

            // Inisialisasi chart
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                    datasets: [
                        {
                            label: 'Hadir',
                            data: [90, 30, 50, 70],
                            backgroundColor: '#28a745',
                        },
                        {
                            label: 'Izin/Sakit',
                            data: [10, 25, 30, 40],
                            backgroundColor: '#ffc107',
                        },
                        {
                            label: 'Alpa',
                            data: [5, 20, 10, 75],
                            backgroundColor: '#dc3545',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Statistik Kehadiran Bulan Ini'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Persentase (%)'
                            }
                        }
                    }
                }
            });

            // Inisialisasi kalender
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'id',
                events: [
                    {
                        title: 'Rapat Guru',
                        start: '2023-07-05',
                        color: '#ffc107'
                    },
                    {
                        title: 'UTS Semester 1',
                        start: '2023-08-14',
                        end: '2023-08-18',
                        color: '#dc3545'
                    },
                    {
                        title: 'Libur Nasional',
                        start: '2023-08-17',
                        color: '#28a745'
                    }
                ]
            });
            calendar.render();

            // Toggle sidebar (jika ada tombol toggle)
            $('#sidebarToggle').on('click', function() {
                $('.sidebar').toggleClass('d-none');
            });

            // --- AJAX untuk memuat data siswa di form absensi ---
            $('#loadSiswaBtn').on('click', function() {
                var id_kelas = $('#selectKelasAbsensi').val();
                var tanggal_absensi = $('input[name="tanggal_absensi"]').val();
                var id_mapel_absensi = $('select[name="id_mapel_absensi"]').val();
                var id_guru_absensi = $('select[name="id_guru_absensi"]').val();

                if (!id_kelas || !tanggal_absensi || !id_mapel_absensi || !id_guru_absensi) {
                    alert('Mohon lengkapi Tanggal, Kelas, Mata Pelajaran, dan Guru terlebih dahulu.');
                    return;
                }

                $.ajax({
                    url: 'ajax_handler.php', // Buat file PHP terpisah untuk menangani AJAX
                    type: 'POST',
                    data: {
                        action: 'get_siswa_for_absensi',
                        id_kelas: id_kelas,
                        tanggal: tanggal_absensi,
                        id_mapel: id_mapel_absensi,
                        id_guru: id_guru_absensi
                    },
                    success: function(response) {
                        $('#siswaAbsensiBody').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + error);
                        alert('Gagal memuat data siswa. Silakan coba lagi.');
                    }
                });
            });

            // --- AJAX untuk memuat rekap absensi ---
            function loadRekapAbsensi(month) {
                if ($.fn.DataTable.isDataTable('#rekapAbsensiTable')) {
                    $('#rekapAbsensiTable').DataTable().destroy();
                }
                $.ajax({
                    url: 'ajax_handler.php', // Buat file PHP terpisah untuk menangani AJAX
                    type: 'POST',
                    data: {
                        action: 'get_rekap_absensi',
                        month: month
                    },
                    success: function(response) {
                        $('#rekapAbsensiTable tbody').html(response);
                        $('#rekapAbsensiTable').DataTable({
                            responsive: true,
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + error);
                        alert('Gagal memuat rekap absensi. Silakan coba lagi.');
                    }
                });
            }

            // Muat rekap absensi saat halaman dimuat (untuk bulan saat ini)
            loadRekapAbsensi($('#filterMonth').val());

            // Event listener untuk filter rekap absensi
            $('#filterRekapBtn').on('click', function() {
                var selectedMonth = $('#filterMonth').val();
                loadRekapAbsensi(selectedMonth);
            });

            // Event listener untuk tab change agar DataTable diinisialisasi ulang jika perlu
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                // Periksa apakah tab yang aktif adalah tab 'siswa' atau 'guru' atau 'absensi'
                var targetTab = $(e.target).attr("href"); // activated tab
                if (targetTab === '#siswa' || targetTab === '#guru' || targetTab === '#absensi') {
                    // Hancurkan dan inisialisasi ulang DataTable untuk memastikan responsif
                    $('.datatable').each(function() {
                        if ($.fn.DataTable.isDataTable(this)) {
                            $(this).DataTable().destroy();
                        }
                        $(this).DataTable({
                            responsive: true,
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                            }
                        });
                    });
                }
            });
        });
    </script>
</body>
</html>
