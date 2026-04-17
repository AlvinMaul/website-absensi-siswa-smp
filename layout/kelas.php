<?php
include 'db_connect.php';

// Ambil data kelas beserta wali kelasnya
$sql = "SELECT k.*, g.nama_guru AS nama_wali_kelas FROM kelas k LEFT JOIN guru g ON k.id_wali_kelas = g.id_guru";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kelas</title>
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
        .btn-detail { background-color: #007bff; }
    </style>
</head>
<body>
    <h2>Data Kelas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kelas</th>
                <th>Tingkat</th>
                <th>Wali Kelas</th>
                <th>Tahun Ajaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id_kelas"] . "</td>";
                    echo "<td>" . $row["nama_kelas"] . "</td>";
                    echo "<td>" . $row["tingkat"] . "</td>";
                    echo "<td>" . ($row["nama_wali_kelas"] ? $row["nama_wali_kelas"] : "Belum Ditentukan") . "</td>";
                    echo "<td>" . $row["tahun_ajaran"] . "</td>";
                    echo "<td>";
                    echo "<a href='kelas_detail.php?id=" . $row["id_kelas"] . "' class='btn btn-detail'>Detail</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada data kelas.</td></tr>";
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
