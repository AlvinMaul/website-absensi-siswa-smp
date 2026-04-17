<?php
include 'db_connect.php';

$id_guru = '';
$nip = '';
$nama_guru = '';
$jenis_kelamin = '';
$tempat_lahir = '';
$tanggal_lahir = '';
$alamat = '';
$no_telp = '';
$email = '';
$foto_profil = '';
$form_title = "Tambah Guru Baru";

// Handle Edit (jika ada ID di URL)
if (isset($_GET['id'])) {
    $id_guru = $_GET['id'];
    $sql_select = "SELECT * FROM guru WHERE id_guru = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id_guru);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();
    if ($result_select->num_rows > 0) {
        $row = $result_select->fetch_assoc();
        $nip = $row['nip'];
        $nama_guru = $row['nama_guru'];
        $jenis_kelamin = $row['jenis_kelamin'];
        $tempat_lahir = $row['tempat_lahir'];
        $tanggal_lahir = $row['tanggal_lahir'];
        $alamat = $row['alamat'];
        $no_telp = $row['no_telp'];
        $email = $row['email'];
        $foto_profil = $row['foto_profil'];
        $form_title = "Edit Data Guru";
    } else {
        echo "<script>alert('Data guru tidak ditemukan!'); window.location.href='guru.php';</script>";
    }
    $stmt_select->close();
}

// Handle Form Submission (Tambah atau Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_guru = $_POST['id_guru'];
    $nip = $_POST['nip'];
    $nama_guru = $_POST['nama_guru'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];

    // Handle foto_profil upload (sederhana, tanpa validasi ekstensif)
    $target_dir = "uploads/guru/"; // Pastikan folder ini ada dan writable
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

    if (empty($id_guru)) {
        // Insert New Guru
        $sql_insert = "INSERT INTO guru (nip, nama_guru, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, no_telp, email, foto_profil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssssssss", $nip, $nama_guru, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $no_telp, $email, $foto_profil_name);
        if ($stmt_insert->execute()) {
            echo "<script>alert('Data guru berhasil ditambahkan!'); window.location.href='guru.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt_insert->error . "');</script>";
        }
        $stmt_insert->close();
    } else {
        // Update Existing Guru
        $sql_update = "UPDATE guru SET nip=?, nama_guru=?, jenis_kelamin=?, tempat_lahir=?, tanggal_lahir=?, alamat=?, no_telp=?, email=?, foto_profil=? WHERE id_guru=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssssssi", $nip, $nama_guru, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $no_telp, $email, $foto_profil_name, $id_guru);
        if ($stmt_update->execute()) {
            echo "<script>alert('Data guru berhasil diperbarui!'); window.location.href='guru.php';</script>";
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
    <form action="guru_form.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_guru" value="<?php echo htmlspecialchars($id_guru); ?>">

        <label for="nip">NIP:</label>
        <input type="text" id="nip" name="nip" value="<?php echo htmlspecialchars($nip); ?>" required>

        <label for="nama_guru">Nama Guru:</label>
        <input type="text" id="nama_guru" name="nama_guru" value="<?php echo htmlspecialchars($nama_guru); ?>" required>

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

        <label for="no_telp">No. Telepon:</label>
        <input type="text" id="no_telp" name="no_telp" value="<?php echo htmlspecialchars($no_telp); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

        <label for="foto_profil">Foto Profil:</label>
        <input type="file" id="foto_profil" name="foto_profil"><br>
        <?php if ($foto_profil): ?>
            <p>Foto saat ini: <img src="uploads/guru/<?php echo htmlspecialchars($foto_profil); ?>" width="50"></p>
            <input type="hidden" name="current_foto_profil" value="<?php echo htmlspecialchars($foto_profil); ?>">
        <?php endif; ?>
        <br>

        <button type="submit">Simpan</button>
    </form>
    <a href="guru.php" class="back-link">Kembali ke Data Guru</a>
</body>
</html>

<?php
$conn->close();
?>
