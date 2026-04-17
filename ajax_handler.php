<?php
// Koneksi database (pastikan path ke file koneksi Anda benar jika terpisah)
$host = "localhost";
$username = "root";
$password = "";
$database = "absensi_siswa";
$conn = mysqli_connect($host, $username, $password, $database);

if(!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi untuk mencegah SQL injection
function clean_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'get_siswa_for_absensi') {
        $id_mapel = clean_input($_POST['id_mapel']);
        $id_guru = clean_input($_POST['id_guru']);
        // Perbaikan: gunakan $conn bukan $koneksi
        $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
        $id_kelas = mysqli_real_escape_string($conn, $_POST['id_kelas']);

        // Perbaikan: gunakan $query bukan $sql
        $query = "SELECT s.id_siswa, s.nis, s.nama_siswa, k.nama_kelas, 
                         a.tanggal, ja.kode_absensi, a.keterangan
                  FROM siswa s
                  JOIN kelas k ON s.id_kelas = k.id_kelas
                  LEFT JOIN absensi a ON s.id_siswa = a.id_siswa AND a.tanggal = '$tanggal'
                  LEFT JOIN jenis_absensi ja ON a.id_jenis_absensi = ja.id_jenis_absensi
                  WHERE k.id_kelas = '$id_kelas'
                  ORDER BY s.nama_siswa";

        // Perbaikan: gunakan $query bukan $sql
        $result = mysqli_query($conn, $query);

        $output = '';
        if (mysqli_num_rows($result) > 0) {
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $current_status = $row['kode_absensi'] ?? 'H'; // Default ke Hadir jika belum ada absensi
                $keterangan_siswa = $row['keterangan'] ?? '';

                $output .= '<tr>';
                $output .= '<td>' . $no++ . '</td>';
                $output .= '<td>' . $row['nis'] . '</td>';
                $output .= '<td>' . $row['nama_siswa'] . '</td>';
                $output .= '<td>';
                $output .= '<div class="btn-group btn-group-sm" role="group">';
                $output .= '<input type="radio" class="btn-check" name="status_' . $row['id_siswa'] . '" id="hadir_' . $row['id_siswa'] . '" autocomplete="off" value="H" ' . ($current_status == 'H' ? 'checked' : '') . '>';
                $output .= '<label class="btn btn-outline-success" for="hadir_' . $row['id_siswa'] . '">Hadir</label>';
                
                $output .= '<input type="radio" class="btn-check" name="status_' . $row['id_siswa'] . '" id="izin_' . $row['id_siswa'] . '" autocomplete="off" value="I" ' . ($current_status == 'I' ? 'checked' : '') . '>';
                $output .= '<label class="btn btn-outline-warning" for="izin_' . $row['id_siswa'] . '">Izin</label>';
                
                $output .= '<input type="radio" class="btn-check" name="status_' . $row['id_siswa'] . '" id="sakit_' . $row['id_siswa'] . '" autocomplete="off" value="S" ' . ($current_status == 'S' ? 'checked' : '') . '>';
                $output .= '<label class="btn btn-outline-info" for="sakit_' . $row['id_siswa'] . '">Sakit</label>';
                
                $output .= '<input type="radio" class="btn-check" name="status_' . $row['id_siswa'] . '" id="alpa_' . $row['id_siswa'] . '" autocomplete="off" value="A" ' . ($current_status == 'A' ? 'checked' : '') . '>';
                $output .= '<label class="btn btn-outline-danger" for="alpa_' . $row['id_siswa'] . '">Alpa</label>';
                $output .= '</div>';
                $output .= '<input type="hidden" name="siswa_id[]" value="' . $row['id_siswa'] . '">'; // Hidden input untuk mengirim ID siswa
                $output .= '</td>';
                $output .= '<td><input type="text" class="form-control form-control-sm" name="keterangan_' . $row['id_siswa'] . '" placeholder="keterangan" value="' . htmlspecialchars($keterangan_siswa) . '"></td>';
                $output .= '</tr>';
            }
        } else {
            $output = '<tr><td colspan="5" class="text-center">Tidak ada siswa di kelas ini.</td></tr>';
        }
        echo $output;
        
    } elseif ($_POST['action'] == 'get_rekap_absensi') {
        $month_year = clean_input($_POST['month']); // Format YYYY-MM
        $year = date('Y', strtotime($month_year));
        $month = date('m', strtotime($month_year));

        $sql = "SELECT 
                    s.nis,
                    s.nama_siswa,
                    k.nama_kelas,
                    COUNT(CASE WHEN ja.kode_absensi = 'H' AND YEAR(a.tanggal) = '$year' AND MONTH(a.tanggal) = '$month' THEN 1 END) AS hadir,
                    COUNT(CASE WHEN ja.kode_absensi IN ('I','S') AND YEAR(a.tanggal) = '$year' AND MONTH(a.tanggal) = '$month' THEN 1 END) AS izin_sakit,
                    COUNT(CASE WHEN ja.kode_absensi = 'A' AND YEAR(a.tanggal) = '$year' AND MONTH(a.tanggal) = '$month' THEN 1 END) AS alpa,
                    COUNT(a.id_absensi) AS total_absensi_bulan_ini
                FROM 
                    siswa s
                JOIN 
                    kelas k ON s.id_kelas = k.id_kelas
                LEFT JOIN 
                    absensi a ON s.id_siswa = a.id_siswa
                LEFT JOIN 
                    jenis_absensi ja ON a.id_jenis_absensi = ja.id_jenis_absensi
                WHERE 
                    YEAR(a.tanggal) = '$year' AND MONTH(a.tanggal) = '$month'
                    OR a.tanggal IS NULL
                GROUP BY 
                    s.id_siswa, s.nis, s.nama_siswa, k.nama_kelas
                ORDER BY k.nama_kelas, s.nama_siswa";
                
        $result = mysqli_query($conn, $sql);

        $output = '';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $total_absensi = $row['hadir'] + $row['izin_sakit'] + $row['alpa'];
                $persentase = ($total_absensi > 0) ? round(($row['hadir'] / $total_absensi) * 100, 2) : 0;
                $progress_color = 'bg-success';
                if ($persentase < 80 && $persentase >= 60) {
                    $progress_color = 'bg-warning';
                } elseif ($persentase < 60) {
                    $progress_color = 'bg-danger';
                }

                $output .= '<tr>';
                $output .= '<td>' . htmlspecialchars($row['nis']) . '</td>';
                $output .= '<td>' . htmlspecialchars($row['nama_siswa']) . '</td>';
                $output .= '<td>' . htmlspecialchars($row['nama_kelas']) . '</td>';
                $output .= '<td>' . $row['hadir'] . '</td>';
                $output .= '<td>' . $row['izin_sakit'] . '</td>';
                $output .= '<td>' . $row['alpa'] . '</td>';
                $output .= '<td>';
                $output .= '<div class="progress">';
                $output .= '<div class="progress-bar ' . $progress_color . '" role="progressbar" style="width: ' . $persentase . '%;" aria-valuenow="' . $persentase . '" aria-valuemin="0" aria-valuemax="100">' . $persentase . '%</div>';
                $output .= '</div>';
                $output .= '</td>';
                $output .= '</tr>';
            }
        } else {
            $output = '<tr><td colspan="7" class="text-center">Tidak ada data rekap absensi untuk bulan ini.</td></tr>';
        }
        echo $output;
    }
}

mysqli_close($conn);
?>