<?php
// Include database connection
include('includes/dbconnection.php');

// Validasi koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>U-WIBU || Dashboard</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="vendors/chartist/chartist.min.css">
    <!-- Layout styles -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <?php include_once('includes/header.php'); ?>
        <div class="page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <?php include_once('includes/sidebar.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-sm-flex align-items-baseline report-summary-header">
                                                <h5 class="font-weight-semibold">Report Summary</h5>
                                                <span class="ml-auto">Updated Report</span>
                                                <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row report-inner-cards-wrapper mb-5">
                                        <!-- Total Murid -->
                                        <div class="col-md-6 col-xl report-inner-card">
                                            <div class="inner-card-text">
                                                <?php
                                                $sql = "SELECT COUNT(*) AS total_students FROM tb_student";
                                                $result = mysqli_query($conn, $sql);
                                                $row = mysqli_fetch_assoc($result);
                                                $total_students = $row['total_students'];
                                                ?>
                                                <span class="report-title">Total Murid</span>
                                                <h4><?php echo htmlentities($total_students); ?></h4>
                                                <a href="manage-students.php"><span class="report-count">Lihat Murid</span></a>
                                            </div>
                                            <div class="inner-card-icon bg-danger">
                                                <i class="icon-user"></i>
                                            </div>
                                        </div>
                                        <!-- Total Jurusan -->
                                        <div class="col-md-6 col-xl report-inner-card">
                                            <div class="inner-card-text">
                                                <?php
                                                $sql = "SELECT COUNT(DISTINCT jurusan) AS total_jurusan FROM tb_student";
                                                $result = mysqli_query($conn, $sql);
                                                $row = mysqli_fetch_assoc($result);
                                                $total_jurusan = $row['total_jurusan'];
                                                ?>
                                                <span class="report-title">Total Jurusan</span>
                                                <h4><?php echo htmlentities($total_jurusan); ?></h4>
                                                <a href="#"><span class="report-count">Lihat Jurusan</span></a>
                                            </div>
                                            <div class="inner-card-icon bg-success">
                                                <i class="icon-notebook"></i>
                                            </div>
                                        </div>
                                        <!-- Total Angkatan -->
                                        <div class="col-md-6 col-xl report-inner-card">
                                            <div class="inner-card-text">
                                                <?php
                                                $sql = "SELECT COUNT(DISTINCT angkatan) AS total_angkatan FROM tb_student";
                                                $result = mysqli_query($conn, $sql);
                                                $row = mysqli_fetch_assoc($result);
                                                $total_angkatan = $row['total_angkatan'];
                                                ?>
                                                <span class="report-title">Total Angkatan</span>
                                                <h4><?php echo htmlentities($total_angkatan); ?></h4>
                                                <a href="#"><span class="report-count">Lihat Angkatan</span></a>
                                            </div>
                                            <div class="inner-card-icon bg-warning">
                                                <i class="icon-graduation"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <h5>Distribusi Angkatan</h5>
                                            <canvas id="angkatanPieChart"></canvas>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Distribusi Jurusan</h5>
                                            <canvas id="jurusanDoughnutChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- Fetch data for charts -->
    <?php
    // Data untuk chart distribusi jurusan
    $sql = "SELECT jurusan, COUNT(*) AS count FROM tb_student GROUP BY jurusan";
    $result = mysqli_query($conn, $sql);
    $jurusanLabels = [];
    $jurusanCounts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jurusanLabels[] = $row['jurusan'];
        $jurusanCounts[] = $row['count'];
    }

    // Data untuk chart distribusi angkatan
    $sql = "SELECT angkatan, COUNT(*) AS count FROM tb_student GROUP BY angkatan ORDER BY angkatan ASC";
    $result = mysqli_query($conn, $sql);
    $angkatanLabels = [];
    $angkatanCounts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $angkatanLabels[] = $row['angkatan'];
        $angkatanCounts[] = $row['count'];
    }
    ?>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Chart untuk distribusi angkatan
            const ctxAngkatan = document.getElementById('angkatanPieChart').getContext('2d');
            new Chart(ctxAngkatan, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($angkatanLabels); ?>,
                    datasets: [{
                        data: <?php echo json_encode($angkatanCounts); ?>,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    }]
                }
            });

            // Chart untuk distribusi jurusan
            const ctxJurusan = document.getElementById('jurusanDoughnutChart').getContext('2d');
            new Chart(ctxJurusan, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($jurusanLabels); ?>,
                    datasets: [{
                        data: <?php echo json_encode($jurusanCounts); ?>,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    }]
                }
            });
        });
    </script>

    <!-- Plugins -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
</body>

</html>
