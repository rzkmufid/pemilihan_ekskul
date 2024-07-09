<?php
// Query SQL untuk mengambil data dari tabel Kepuasan
$sql = "SELECT * FROM kuesioner";
$result = $conn->query($sql);
?>

<?php

    if (isset($_GET["notif"])) {
        if ($_GET["notif"] == "delete") {
            echo '<div class="alert alert-danger" role="alert">
                    Data Berhasil dihapus!
                </div>';
        }
        if ($_GET["notif"] == "edit") {
            echo '<div class="alert alert-success" role="alert">
                    Data Berhasil Dirubah.
                </div>';
        }
    }

    function convertRating($rating){
        switch ($rating) {
            case '1':
                $hasil =  "Sangat Tidak Puas";
                break;
            case '2':
                $hasil =  "Tidak Puas";
                break;
            case '3':
                $hasil =  "Netral";
                break;
            case '4':
                $hasil =  "Puas";
            break;
            case '5':
                $hasil =  "Sangat Puas";
                break;
            default:
                $hasil = "";
                break;
    
            }
        return $hasil;
    }


?>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Pengaduan</h4>
            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Usia</th>
                        <th>Jenis</th>
                        <th>Rasa Kopi</th>
                        <th>Variasi Menu</th>
                        <th>Keramahan Staf</th>
                        <th>Kecepatan Layanan</th>
                        <th>Harga</th>
                        <th>Kenyamanan</th>
                        <th>Kebersihan</th>
                        <th>Kepuasan Keseluruhan</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        <?php
                            if ($result->num_rows > 0) {
                                // Menampilkan data untuk setiap baris
                                $no = 1;
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["usia"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["jenis_kelamin"]) . "</td>";
                                    echo "<td>" . convertRating($row["rasa_kopi"]) . "</td>";
                                    echo "<td>" . convertRating($row["variasi_menu"]) . "</td>";
                                    echo "<td>" . convertRating($row["keramahan_staf"]) . "</td>";
                                    echo "<td>" . convertRating($row["kecepatan_layanan"]) . "</td>";
                                    echo "<td>" . convertRating($row["harga"]) . "</td>";
                                    echo "<td>" . convertRating($row["kenyamanan"]) . "</td>";
                                    echo "<td>" . convertRating($row["kebersihan"]) . "</td>";
                                    echo "<td>" . convertRating($row["kepuasan_keseluruhan"]) . "</td>";
                                    echo '<td>
                                            <a href="page/hapus/hapusKepuasan.php?id_kepuasan=' . $row["id"] . '"><button type="button" class="btn btn-danger btn-rounded btn-icon">
                                            <i class="ti-trash"></i>
                                        </button></a>
                                            </td>';
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='18'>Tidak ada data</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>