<?php
// Include koneksi ke database
include 'koneksi.php';

// Inisialisasi variabel untuk edit
$id_edit = $nm_ekskul_edit = '';

// Proses jika form tambah ekskul dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_ekskul'])) {
    $nm_ekskul = $_POST['nm_ekskul'];
    // Query SQL untuk menambah ekskul baru
    $sql_insert = "INSERT INTO ekskul (nm_ekskul) VALUES ('$nm_ekskul')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "<script>
                alert('Data ekskul berhasil ditambahkan');
                window.location.href = 'index.php?i=ekskul';
             </script>";
        exit();
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}

// Proses jika form edit ekskul dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_ekskul'])) {
    $id_edit = $_POST['id_edit'];
    $nm_ekskul_edit = $_POST['nm_ekskul_edit'];
    // Query SQL untuk update ekskul
    $sql_update = "UPDATE ekskul SET nm_ekskul = '$nm_ekskul_edit' WHERE kd_ekskul = $id_edit";
    if ($conn->query($sql_update) === TRUE) {
        echo "<script>
                alert('Data ekskul berhasil diupdate');
                window.location.href = 'index.php?i=ekskul';
             </script>";
        exit();
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}

// Proses jika tombol hapus ekskul diklik
if (isset($_POST['hapus_ekskul'])) {
    $id_hapus = $_POST['id_hapus'];
    // Mulai transaksi
    $conn->begin_transaction();
    try {
        // Hapus ekskul dari tabel ekskul
        $sql_delete_ekskul = "DELETE FROM ekskul WHERE kd_ekskul = $id_hapus";
        if ($conn->query($sql_delete_ekskul) === TRUE) {
            // Commit transaksi jika berhasil
            $conn->commit();
            echo "<script>
                    alert('Data ekskul berhasil dihapus');
                    window.location.href = 'index.php?i=ekskul';
                 </script>";
            exit();
        } else {
            throw new Exception("Error deleting ekskul: " . $conn->error);
        }
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

// Query SQL untuk mengambil data dari tabel ekskul
$sql = "SELECT * FROM ekskul";
$result = $conn->query($sql);
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Ekskul</h4>
            <!-- Tombol Tambah Ekskul -->
            <div class="col-lg-12 mb-4">
                <button type="button" class="btn btn-primary mb-2" onclick="openTambahModal()">
                    <i class="bi bi-plus"></i> Tambah Ekskul
                </button>
            </div>

            <!-- Tabel Data Ekskul -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ekskul</th>
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
                                echo "<td>" . $row["nm_ekskul"] . "</td>";
                                echo '<td>
                                        <button type="button" class="btn btn-primary btn-rounded btn-icon"
                                            onclick="openEditModal(' . $row["kd_ekskul"] . ', \'' . $row["nm_ekskul"] . '\')">
                                            <i class="ti-pencil-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-rounded btn-icon"
                                            onclick="openHapusModal(' . $row["kd_ekskul"] . ')">
                                            <i class="ti-trash"></i>
                                        </button>
                                      </td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>Tidak ada data</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Ekskul -->
<div class="modal fade" id="modalTambahEkskul" tabindex="-1" role="dialog" aria-labelledby="modalTambahEkskulLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahEkskulLabel">Tambah Ekskul Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nm_ekskul">Nama Ekskul</label>
                        <input type="text" class="form-control" id="nm_ekskul" name="nm_ekskul" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="tambah_ekskul">Tambah Ekskul</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Ekskul -->
<div class="modal fade" id="modalEditEkskul" tabindex="-1" role="dialog" aria-labelledby="modalEditEkskulLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditEkskulLabel">Edit Ekskul</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_edit" name="id_edit" value="">
                    <div class="form-group">
                        <label for="nm_ekskul_edit">Nama Ekskul</label>
                        <input type="text" class="form-control" id="nm_ekskul_edit" name="nm_ekskul_edit" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="edit_ekskul">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Ekskul -->
<div class="modal fade" id="modalHapusEkskul" tabindex="-1" role="dialog" aria-labelledby="modalHapusEkskulLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHapusEkskulLabel">Hapus Ekskul</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_hapus" name="id_hapus" value="">
                    <p>Apakah Anda yakin ingin menghapus ekskul ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" name="hapus_ekskul">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk membuka modal tambah ekskul
    function openTambahModal() {
        $('#modalTambahEkskul').modal('show');
    }

    // Fungsi untuk membuka modal edit ekskul dan mengisi nilai
    function openEditModal(id, nm_ekskul) {
        var modalEdit = document.getElementById('modalEditEkskul');
        var idEditInput = modalEdit.querySelector('#id_edit');
        var nmEkskulEditInput = modalEdit.querySelector('#nm_ekskul_edit');

        idEditInput.value = id;
        nmEkskulEditInput.value = nm_ekskul;

        $('#modalEditEkskul').modal('show');
    }

    // Fungsi untuk membuka modal hapus ekskul dan mengisi nilai
    function openHapusModal(id) {
        var modalHapus = document.getElementById('modalHapusEkskul');
        var idHapusInput = modalHapus.querySelector('#id_hapus');

        idHapusInput.value = id;

        $('#modalHapusEkskul').modal('show');
    }
</script>

<?php
// Tutup koneksi database
$conn->close();
?>