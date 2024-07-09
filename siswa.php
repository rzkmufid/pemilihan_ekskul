<?php
// Query SQL untuk mengambil data dari tabel user
$sql = "SELECT * FROM siswa";
$result = $conn->query($sql);
?>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Siswa</h4>
            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // Menampilkan data untuk setiap baris
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . $row["nm_siswa"] . "</td>";
                                echo "<td>" . $row["alamat"] . "</td>";
                                echo "<td>" . $row["no_telepon"] . "</td>";
                                echo "<td>" . $row["jenis_kelamin"] . "</td>";
                                echo "<td>" . $row["tanggal_lahir"] . "</td>";
                                echo "<td>" . $row["kelas"] . "</td>";
                                echo '<td>
                                        <button type="button" class="btn btn-inverse-info btn-icon">
                                            <i class="ti-notepad"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-rounded btn-icon">
                                            <i class="ti-pencil-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-rounded btn-icon">
                                            <i class="ti-trash"></i>
                                        </button>
                                        </td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Tidak ada data</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>