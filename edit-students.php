<?php
include('includes/dbconnection.php'); // Koneksi ke database
include('includes/function.php');    // File berisi fungsi tambahan (opsional)

// Periksa apakah parameter 'id' tersedia
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // Ambil dan validasi ID dari URL

    // Ambil data mahasiswa berdasarkan ID
    $query = "SELECT * FROM tb_student WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah data mahasiswa ditemukan
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc(); // Simpan data mahasiswa dalam array
    } else {
        echo "<script>alert('Mahasiswa tidak ditemukan!');</script>";
        echo "<script>window.location.href='view-students.php';</script>";
        exit();
    }
    $stmt->close();
} else {
    echo "<script>alert('ID mahasiswa tidak valid!');</script>";
    echo "<script>window.location.href='view-students.php';</script>";
    exit();
}

// Tangani form submit untuk update data mahasiswa
if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['stuname']);
    $nim = mysqli_real_escape_string($conn, $_POST['stuid']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['stuclass']);
    $angkatan = mysqli_real_escape_string($conn, $_POST['stuangkatan']);

    // Update data mahasiswa di database
    $update_query = "UPDATE tb_student SET nama = ?, nim = ?, jurusan = ?, angkatan = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssii", $nama, $nim, $jurusan, $angkatan, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data mahasiswa berhasil diperbarui!');</script>";
        echo "<script>window.location.href='view-students.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui data mahasiswa.');</script>";
    }
    $stmt->close();
}
?>
