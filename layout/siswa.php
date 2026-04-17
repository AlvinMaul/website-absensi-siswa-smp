<?php
include 'db_connect.php';

$id_siswa = '';
$nis = '';
$nisn = '';
$nama_siswa = '';
$jenis_kelamin = '';
$tempat_lahir = '';
$tanggal_lahir = '';
$alamat = '';
$id_kelas = '';
$nama_ayah = '';
$nama_ibu = '';
$no_telp_ortu = '';
$foto_profil = '';
$form_title = "Tambah Siswa Baru";

// Ambil data kelas untuk dropdown
$kelas_options = [];
$sql_kelas = "SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas";
$result_kelas = $conn->query($sql_kelas);
if ($result_kelas->num_rows > 0) {
    while($row_kelas = $result_kelas->fetch_assoc()) {
        $kelas_options[] = $row_kelas;
    }
}

// Handle Edit (jika ada ID di URL)
if (isset($_GET['id'])) {
    $id_siswa = $_GET['id'];
    $sql_select = "SELECT * FROM siswa WHERE id_siswa = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id_siswa);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();
    if ($result_select->num_rows > 0) {
        $row = $result_select->fetch_assoc();
        $nis = $row['nis'];
        $nisn = $row['nisn'];
        $nama_siswa = $row['nama_siswa'];
        $jenis_kelamin = $row['jenis_kelamin'];
        $tempat_lahir = $row['tempat_lahir'];
        $tanggal_lahir = $row['tanggal_lahir'];
        $alamat = $row['alamat'];
        $id_kelas = $row['id_kelas'];
        $nama_ayah = $row['nama_ayah'];
        $nama_ibu = $row['nama_ibu'];
        $no_telp_ortu = $row['no_telp_ortu'];
        $foto_profil = $row['foto_profil'];
        $form_title = "Edit Data Siswa";
    } else {
        echo "<script>alert('Data siswa tidak ditemukan!'); window.location.href='siswa.php';</script>";
    }
    $stmt_select->close();
}

// Handle Form Submission (Tambah atau Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_siswa = $_POST['id_siswa'];
    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $nama_siswa = $_POST['nama_siswa'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $id_kelas = $_POST['id_kelas'];
    $nama_ayah = $_POST['nama_ayah'];
    $nama_ibu = $_POST['nama_ibu'];
    $no_telp_ortu = $_POST['no_telp_ortu'];

    // Handle foto_profil upload (sederhana, tanpa validasi ekstensif)
    $target_dir = "uploads/siswa/"; // Pastikan folder ini ada dan writable
    $foto_profil_name = $foto_profil; // Default to existing if no new upload

    if (!empty($_FILES["foto_profil"]["name"])) {
        $foto_profil_name = basename($_FILES["foto_profil"]["name"]);
        $target_file = $target_dir . $foto_profil_name;
        if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
            // File uploaded successfully
        } else {
            echo "<script>alert('Gagal mengupload foto profil.');</script>";
            $foto_profil_name = $foto_profil; // Revert if upload fails
        }
    }

    if (empty($id_siswa)) {
        // Insert New Siswa
        $sql_insert = "INSERT INTO siswa (nis, nisn, nama_siswa, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, id_kelas, nama_ayah, nama_ibu, no_telp_ortu, foto_profil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssssssssss", $nis, $nisn, $nama_siswa, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $id_kelas, $nama_ayah, $nama_ibu, $no_telp_ortu, $foto_profil_name);
        if ($stmt_insert->execute()) {
            echo "<script>alert('Data siswa berhasil ditambahkan!'); window.location.href='siswa.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt_insert->error . "');</script>";
        }
        $stmt_insert->close();
    } else {
        // Update Existing Siswa
        $sql_update = "UPDATE siswa SET nis=?, nisn=?, nama_siswa=?, jenis_kelamin=?, tempat_lahir=?, tanggal_lahir=?, alamat=?, id_kelas=?, nama_ayah=?, nama_ibu=?, no_telp_ortu=?, foto_profil=? WHERE id_siswa=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssssssssssi", $nis, $nisn, $nama_siswa, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $id_kelas, $nama_ayah, $nama_ibu, $no_telp_ortu, $foto_profil_name, $id_siswa);
        if ($stmt_update->execute()) {
            echo "<script>alert('Data siswa berhasil diperbarui!'); window.location.href='siswa.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt_update->error . "');</script>";
        }
        $stmt_update->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $form_title; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        form { background-color: #f9f9f9; padding: 20px; border-radius: 8px; max-width: 600px; margin-top: 20px; }
        form label { display: block; margin-bottom: 8px; font-weight: bold; }
        form input[type="text"],
        form input[type="email"],
        form input[type="date"],
        form textarea,
        form select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        form input[type="radio"] { margin-right: 5px; }
        form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        form button:hover { background-color: #45a049; }
        .back-link { display: block; margin-top: 20px; text-decoration: none; color: #008CBA; }
    </style>
</head>
<body>
    <h2><?php echo $form_title; ?></h2>
    <form action="siswa_form.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_siswa" value="<?php echo htmlspecialchars($id_siswa); ?>">

        <label for="nis">NIS:</label>
        <input type="text" id="nis" name="nis" value="<?php echo htmlspecialchars($nis); ?>" required>

        <label for="nisn">NISN:</label>
        <input type="text" id="nisn" name="nisn" value="<?php echo htmlspecialchars($nisn); ?>">

        <label for="nama_siswa">Nama Siswa:</label>
        <input type="text" id="nama_siswa" name="nama_siswa" value="<?php echo htmlspecialchars($nama_siswa); ?>" required>

        <label>Jenis Kelamin:</label><br>
        <input type="radio" id="laki_laki" name="jenis_kelamin" value="Laki-laki" <?php echo ($jenis_kelamin == 'Laki-laki') ? 'checked' : ''; ?> required>
        <label for="laki_laki">Laki-laki</label>
        <input type="radio" id="perempuan" name="jenis_kelamin" value="Perempuan" <?php echo ($jenis_kelamin == 'Perempuan') ? 'checked' : ''; ?> required>
        <label for="perempuan">Perempuan</label><br><br>

        <label for="tempat_lahir">Tempat Lahir:</label>
        <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($tempat_lahir); ?>">

        <label for="tanggal_lahir">Tanggal Lahir:</label>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir); ?>">

        <label for="alamat">Alamat:</label>
        <textarea id="alamat" name="alamat"><?php echo htmlspecialchars($alamat); ?></textarea>

        <label for="id_kelas">Kelas:</label>
        <select id="id_kelas" name="id_kelas" required>
            <option value="">-- Pilih Kelas --</option>
            <?php foreach ($kelas_options as $kelas): ?>
                <option value="<?php echo $kelas['id_kelas']; ?>" <?php echo ($id_kelas == $kelas['id_kelas']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($kelas['nama_kelas']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="nama_ayah">Nama Ayah:</label>
        <input type="text" id="nama_ayah" name="nama_ayah" value="<?php echo htmlspecialchars($nama_ayah); ?>">

        <label for="nama_ibu">Nama Ibu:</label>
        <input type="text" id="nama_ibu" name="nama_ibu" value="<?php echo htmlspecialchars($nama_ibu); ?>">

        <label for="no_telp_ortu">No. Telepon Orang Tua:</label>
        <input type="text" id="no_telp_ortu" name="no_telp_ortu" value="<?php echo htmlspecialchars($no_telp_ortu); ?>">

        <label for="foto_profil">Foto Profil:</label>
        <input type="file" id="foto_profil" name="foto_profil"><br>
        <?php if ($foto_profil): ?>
            <p>Foto saat ini: <img src="uploads/siswa/<?php echo htmlspecialchars($foto_profil); ?>" width="50"></p>
            <input type="hidden" name="current_foto_profil" value="<?php echo htmlspecialchars($foto_profil); ?>">
        <?php endif; ?>
        <br>

        <button type="submit">Simpan</button>
    </form>
    <a href="siswa.php" class="back-link">Kembali ke Data Siswa</a>
</body>
</html>

<?php
$conn->close();
?>
