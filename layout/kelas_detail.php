<?php
include 'db_connect.php';

$id_kelas = '';
$kelas_info = null;
$siswa_di_kelas = [];

if (isset($_GET['id'])) {
    $id_kelas = $_GET['id'];

    // Ambil informasi detail kelas
    $sql_kelas_detail = "SELECT k.*, g.nama_guru AS nama_wali_kelas 
                         FROM kelas k 
                         LEFT JOIN guru g ON k.id_wali_kelas = g.id_guru 
                         WHERE k.id_kelas = ?";
    $stmt_kelas_detail = $conn->prepare($sql_kelas_detail);
    $stmt_kelas_detail->bind_param("i", $id_kelas);
    $stmt_kelas_detail->execute();
    $result_kelas_detail = $stmt_kelas_detail->get_result();
    if ($result_kelas_detail->num_rows > 0) {
        $kelas_info = $result_kelas_detail->fetch_assoc();
    } else {
        echo "<script>alert('Kelas tidak ditemukan!'); window.location.href='kelas.php';</script>";
        exit();
    }
    $stmt_kelas_detail->close();

    // Ambil daftar siswa di kelas ini
    $sql_siswa_kelas = "SELECT id_siswa, nis, nama_siswa, jenis_kelamin FROM siswa WHERE id_kelas = ? ORDER BY nama_siswa";
    $stmt_siswa_kelas = $conn->prepare($sql_siswa_kelas);
    $stmt_siswa_kelas->bind_param("i", $id_kelas);
    $stmt_siswa_kelas->execute();
    $result_siswa_kelas = $stmt_siswa_kelas->get_result();
    if ($result_siswa_kelas->num_rows > 0) {
        while($row_siswa = $result_siswa_kelas->fetch_assoc()) {
            $siswa_di_kelas[] = $row_siswa;
        }
    }
    $stmt_siswa_kelas->close();

} else {
    echo "<script>alert('ID Kelas tidak ditemukan!'); window.location.href='kelas.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kelas: <?php echo htmlspecialchars($kelas_info['nama_kelas']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        .detail-box { background-color: #f9f9f9; padding: 20px; border-radius: 8px; max-width: 800px; margin-top: 20px; }
        .detail-box p { margin-bottom: 10px; }
        .detail-box strong { display: inline-block; width: 150px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .back-link { display: block; margin-top: 20px; text-decoration: none; color: #008CBA; }
    </style>
</head>
<body>
    <h2>Detail Kelas: <?php echo htmlspecialchars($kelas_info['nama_kelas']); ?></h2>

    <div class="detail-box">
        <p><strong>Nama Kelas:</strong> <?php echo htmlspecialchars($kelas_info['nama_kelas']); ?></p>
        <p><strong>Tingkat:</strong> <?php echo htmlspecialchars($kelas_info['tingkat']); ?></p>
        <p><strong>Wali Kelas:</strong> <?php echo htmlspecialchars($kelas_info['nama_wali_kelas'] ? $kelas_info['nama_wali_kelas'] : "Belum Ditentukan"); ?></p>
        <p><strong>Tahun Ajaran:</strong> <?php echo htmlspecialchars($kelas_info['tahun_ajaran']); ?></p>
        <p><strong>Keterangan:</strong> <?php echo htmlspecialchars($kelas_info['keterangan'] ? $kelas_info['keterangan'] : "-"); ?></p>
    </div>

    <h3>Daftar Siswa di Kelas Ini</h3>
    <?php if (!empty($siswa_di_kelas)): ?>
        <table>
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($siswa_di_kelas as $siswa): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($siswa['nis']); ?></td>
                        <td><?php echo htmlspecialchars($siswa['nama_siswa']); ?></td>
                        <td><?php echo htmlspecialchars($siswa['jenis_kelamin']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada siswa di kelas ini.</p>
    <?php endif; ?>

    <a href="kelas.php" class="back-link">Kembali ke Daftar Kelas</a>
</body>
</html>

<?php
$conn->close();
?>
