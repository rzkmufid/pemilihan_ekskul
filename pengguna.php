<?php
// Query SQL untuk mengambil data dari tabel user
$sql = "SELECT id, username, email, level FROM user";
$result = $conn->query($sql);
?>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Pengguna</h4>
            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Level</th>
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
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["level"] . "</td>";
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