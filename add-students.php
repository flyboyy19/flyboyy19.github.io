<?php
include('includes/dbconnection.php'); // Koneksi ke database
include('includes/function.php');    // File ini bisa berisi fungsi tambahan (jika diperlukan)

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama = mysqli_real_escape_string($conn, $_POST['stuname']);
    $nim = mysqli_real_escape_string($conn, $_POST['stuid']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['stuclass']);
    $angkatan = mysqli_real_escape_string($conn, $_POST['stuangkatan']);

    // Validasi untuk NIM unik
    $check_query = "SELECT nim FROM tb_student WHERE nim=?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('NIM sudah terdaftar. Gunakan NIM lain!');</script>";
    } else {
        // Masukkan data ke database
        $insert_query = "INSERT INTO tb_student (nama, nim, jurusan, angkatan) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssi", $nama, $nim, $jurusan, $angkatan);

        if ($stmt->execute()) {
            echo "<script>alert('Mahasiswa berhasil ditambahkan!');</script>";
            echo "<script>window.location.href='view-students.php';</script>"; // Redirect ke halaman daftar mahasiswa
        } else {
            echo "<script>alert('Terjadi kesalahan, data tidak dapat ditambahkan.');</script>";
        }
    }

    $stmt->close();
}
?>
