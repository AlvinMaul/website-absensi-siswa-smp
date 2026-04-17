<?php
include 'db_connect.php';

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_guru = $_GET['id'];
    $sql_delete = "DELETE FROM guru WHERE id_guru = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_guru);
    if ($stmt_delete->execute()) {
        echo "<script>alert('Data guru berhasil dihapus!'); window.location.href='guru.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt_delete->error . "');</script>";
    }
    $stmt_delete->close();
}

// Ambil data guru
$sql = "SELECT * FROM guru";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn {
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            margin-right: 5px;
        }
        .btn-add { background-color: #4CAF50; }
        .btn-edit { background-color: #008CBA; }
        .btn-delete { background-color: #f44336; }
    </style>
</head>
<body>
    <h2>Data Guru</h2>
    <a href="guru_form.php" class="btn btn-add">Tambah Guru Baru</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NIP</th>
                <th>Nama Guru</th>
                <th>Jenis Kelamin</th>
                <th>Email</th>
                <th>No. Telp</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id_guru"] . "</td>";
                    echo "<td>" . $row["nip"] . "</td>";
                    echo "<td>" . $row["nama_guru"] . "</td>";
                    echo "<td>" . $row["jenis_kelamin"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["no_telp"] . "</td>";
                    echo "<td>";
                    echo "<a href='guru_form.php?id=" . $row["id_guru"] . "' class='btn btn-edit'>Edit</a>";
                    echo "<a href='guru.php?action=delete&id=" . $row["id_guru"] . "' class='btn btn-delete' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Tidak ada data guru.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a href="index.php">Kembali ke Dashboard</a>
</body>
</html>

<?php
$conn->close();
?>
