<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pemilihan_ekskul"; // Sesuaikan dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk mendapatkan rekomendasi nama kriteria
function getRekomendasiKriteria($conn)
{
    $sql = "SELECT nama_kriteria FROM kriteria ORDER BY id_kriteria ASC";
    $result = $conn->query($sql);
    $existing_kriteria = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $existing_kriteria[] = $row['nama_kriteria'];
        }
    }

    // Cari nama kriteria yang hilang
    $suggested_kriteria = [];
    for ($i = 1; $i <= count($existing_kriteria) + 1; $i++) {
        $kriteria_name = 'C' . $i;
        if (!in_array($kriteria_name, $existing_kriteria)) {
            $suggested_kriteria[] = $kriteria_name;
        }
    }

    return $suggested_kriteria;
}

$suggested_kriteria = getRekomendasiKriteria($conn);

// Proses jika form tambah kriteria dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_kriteria'])) {
    $nama_kriteria = $_POST['nama_kriteria'];
    $bobot = $_POST['bobot'];
    $kriteria = $_POST['kriteria'];

    // Query SQL untuk menambah kriteria baru
    $sql_insert = "INSERT INTO kriteria (nama_kriteria, kriteria, bobot) VALUES ('$nama_kriteria', '$kriteria', '$bobot')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "<script>
                alert('Kriteria berhasil ditambahkan');
                window.location.href = 'index.php?i=kriteria';
            </script>";
        exit();
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}

// Proses jika form edit kriteria dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_kriteria'])) {
    $id_kriteria = $_POST['id_kriteria'];
    $bobot = $_POST['bobot'];
    $kriteria = $_POST['kriteria'];

    // Query SQL untuk update kriteria
    $sql_update = "UPDATE kriteria SET bobot = '$bobot', kriteria = '$kriteria' WHERE id_kriteria = '$id_kriteria'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>
                alert('Kriteria berhasil diperbarui');
                window.location.href = 'index.php?i=kriteria';
            </script>";
        exit();
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}

// Proses jika form hapus kriteria dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_kriteria'])) {
    $id_kriteria = $_POST['id_kriteria'];

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Hapus nilai kriteria terkait dari tabel nilai_kriteria
        $sql_delete_nilai_kriteria = "DELETE FROM nilai_kriteria WHERE id_kriteria = '$id_kriteria'";
        if ($conn->query($sql_delete_nilai_kriteria) === FALSE) {
            throw new Exception("Error deleting nilai kriteria: " . $conn->error);
        }

        // Hapus kriteria dari tabel kriteria
        $sql_delete_kriteria = "DELETE FROM kriteria WHERE id_kriteria = '$id_kriteria'";
        if ($conn->query($sql_delete_kriteria) === TRUE) {
            // Commit transaksi jika berhasil
            $conn->commit();
            echo "<script>
                    alert('Kriteria beserta nilai kriteria terkait berhasil dihapus');
                    window.location.href = 'index.php?i=kriteria';
                </script>";
            exit();
        } else {
            throw new Exception("Error deleting kriteria: " . $conn->error);
        }
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

// Query SQL untuk mengambil data dari tabel kriteria
$sql = "SELECT * FROM `kriteria` ORDER BY `kriteria`.`nama_kriteria` ASC";
$result = $conn->query($sql);
?>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Kriteria</h4>
            <!-- Tombol Tambah Kriteria -->
            <div class="col-lg-12 mt-4">
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalTambahKriteria">
                    <i class="bi bi-plus"></i> Tambah Kriteria
                </button>
            </div>

            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kriteria</th>
                            <th>Nama Kriteria</th>
                            <th>Bobot</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . $row["nama_kriteria"] . "</td>";
                                echo "<td>" . $row["kriteria"] . "</td>";
                                echo "<td>" . $row["bobot"] . "</td>";
                                echo '<td>
                                        <form action="index.php?i=kriteria" method="POST">
                                            <input type="hidden" name="id_kriteria" value="' . $row["id_kriteria"] . '">
                                            <button type="button" class="btn btn-inverse-info btn-icon" data-toggle="modal" data-target="#modalDetail' . $row["id_kriteria"] . '">
                                                <i class="ti-notepad"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-rounded btn-icon" data-toggle="modal" data-target="#modalEdit' . $row["id_kriteria"] . '">
                                                <i class="ti-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-rounded btn-icon" data-toggle="modal" data-target="#modalHapus' . $row["id_kriteria"] . '">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </form>
                                      </td>';
                                echo "</tr>";

                                // Modal untuk detail kriteria
                                echo '<div class="modal fade" id="modalDetail' . $row["id_kriteria"] . '" tabindex="-1" role="dialog" aria-labelledby="modalDetail' . $row["id_kriteria"] . 'Label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalDetail' . $row["id_kriteria"] . 'Label">Detail Kriteria</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Nama Kriteria:</strong> ' . $row["nama_kriteria"] . '</p>
                                                    <p><strong>Bobot:</strong> ' . $row["bobot"] . '</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

                                // Modal untuk edit kriteria
                                echo '<div class="modal fade" id="modalEdit' . $row["id_kriteria"] . '" tabindex="-1" role="dialog" aria-labelledby="modalEdit' . $row["id_kriteria"] . 'Label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="index.php?i=kriteria" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEdit' . $row["id_kriteria"] . 'Label">Edit Kriteria</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_kriteria" value="' . $row["id_kriteria"] . '">
                                                        <div class="form-group">
                                                            <label for="nama_kriteria">Kode Kriteria</label>
                                                            <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" value="' . $row["nama_kriteria"] . '" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="kriteria">Nama Kriteria</label>
                                                            <input type="text" class="form-control" id="kriteria" name="kriteria" value="' . $row["kriteria"] . '">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="bobot">Bobot</label>
                                                            <input type="text" class="form-control" id="bobot" name="bobot" value="' . $row["bobot"] . '">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary" name="update_kriteria">Simpan Perubahan</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>';

                                // Modal untuk hapus kriteria
                                echo '<div class="modal fade" id="modalHapus' . $row["id_kriteria"] . '" tabindex="-1" role="dialog" aria-labelledby="modalHapus' . $row["id_kriteria"] . 'Label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="index.php?i=kriteria" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalHapus' . $row["id_kriteria"] . 'Label">Konfirmasi Hapus Kriteria</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Anda yakin ingin menghapus kriteria <strong>' . $row["nama_kriteria"] . '</strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id_kriteria" value="' . $row["id_kriteria"] . '">
                                                        <button type="submit" class="btn btn-danger" name="delete_kriteria">Hapus</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>';
                            }
                        } else {
                            echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kriteria -->
<div class="modal fade" id="modalTambahKriteria" tabindex="-1" role="dialog" aria-labelledby="modalTambahKriteriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="index.php?i=kriteria" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahKriteriaLabel">Tambah Kriteria Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_kriteria">Kode Kriteria</label>
                        <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" value="<?php echo $suggested_kriteria[0]; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="kriteria">Nama Kriteria</label>
                        <input type="text" class="form-control" id="kriteria" name="kriteria" required>
                    </div>
                    <div class="form-group">
                        <label for="bobot">Bobot</label>
                        <input type="text" class="form-control" id="bobot" name="bobot" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="tambah_kriteria">Tambah Kriteria</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Tutup koneksi database
$conn->close();
?>