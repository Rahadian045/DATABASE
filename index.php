<?php
// Koneksi database mysql
$host = "localhost";
$user = "root";
$pass = "";
$database = "dbmahasiswa";

$koneksi = mysqli_connect($host, $user, $pass, $database);

if (!$koneksi) {
    echo "Database tidak terhubung";
}

$nim = "";
$nama = "";
$prodi = "";
$alamat = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'ubah') {
    $nim = $_GET['nim'];
    $query = "SELECT * FROM mahasiswa WHERE NIM ='$nim'";
    $ubah =  mysqli_query($koneksi, $query);
        $tampil = mysqli_fetch_array($ubah);
        $nim = $tampil['NIM'];
        $nama = $tampil['nama_mahasiswa'];
        $prodi = $tampil['Prodi'];
        $alamat = $tampil['Alamat'];
    if ($nim == ''){
        $error = "Data tidak ditemukan";    
    }
}

if ($op == 'hapus') {
    $nim = $_GET['nim'];
    $query = "DELETE FROM mahasiswa WHERE NIM = '$nim'";
    $hapus = mysqli_query($koneksi, $query);
    if ($hapus) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Data gagal dihapus";
    }
}

if (isset($_POST['simpan'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];
    $alamat = $_POST['alamat'];

    if ($nim && $nama && $prodi && $alamat) {
        if ($op == 'ubah') {
            $query = "UPDATE mahasiswa SET nama_mahasiswa = '$nama', Prodi ='$prodi', Alamat = '$alamat' WHERE NIM ='$nim'";
            $ubah = mysqli_query($koneksi, $query);
            if ($ubah) {
                $sukses = "Data Berhasil di simpan";
                $nim = "";
                $nama = "";
                $prodi = "";
                $alamat = "";
            } else {
                $error = "Data Gagal di update";
            }
        } else {
            $query = "INSERT INTO mahasiswa VALUES ('$nim','$nama','$prodi','$alamat')";
            $simpan = mysqli_query($koneksi, $query);
            if ($simpan) {
                $sukses = "Data Berhasil di simpan";
                $nim = "";
                $nama = "";
                $prodi = "";
                $alamat = "";
            } else {
                $error = "Data Gagal di Simpan";
            }
        }
    } else {
        $error = "Silahkan Masukkan Semua Data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <div class="card">
            <div class="card-header text-white bg-primary">
                MASUKKAN DATA MAHASISWA
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                }
                if ($sukses) {
                    echo '<div class="alert alert-success" role="alert">' . $sukses . '</div>';
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">Nim</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="prodi" class="col-sm-2 col-form-label">Prodi</label>
                        <div class="col-sm-10">
                            <select class="form-select" id="prodi" name="prodi">
                                <option selected>- Pilihan Prodi -</option>
                                <option value="Pendidikan Informatika" <?php if ($prodi == 'Pendidikan Informatika') 
                                echo "selected"; ?>>Pendidikan Informatika</option>
                                <option value="Pendidikan Ipa" <?php if ($prodi == 'Pendidikan Ipa') 
                                echo "selected"; ?>>Pendidikan IPA</option>
                                <option value="Pendidikan Guru sekolah dasar" <?php if ($prodi == 'Pendidikan Guru Sekolah dasar') 
                                echo "selected"; ?>>Pendidikan Guru Sekolah dasar</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo $alamat ?></textarea>
                        </div>
                    </div>
                    <div class="col-12" align="right">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header text-white bg-dark">
                DATA MAHASISWA
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIM</th>
                            <th scope="col">NAMA</th>
                            <th scope="col">PRODI</th>
                            <th scope="col">ALAMAT</th>
                            <th scope="col">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM mahasiswa ORDER BY NIM ASC";
                        $tampil = mysqli_query($koneksi, $query);
                        $urut = 1;
                        while ($result = mysqli_fetch_array($tampil)) {
                            $nim = $result['NIM'];
                            $nama = $result['nama_mahasiswa'];
                            $prodi = $result['Prodi'];
                            $alamat = $result['Alamat'];
                            ?>
                            <tr>
                                <th scope="row"><?php echo $urut++; ?></th>
                                <td><?php echo $nim; ?></td>
                                <td><?php echo $nama; ?></td>
                                <td><?php echo $prodi; ?></td>
                                <td><?php echo $alamat; ?></td>
                                <td>
                                    <a href="index.php?op=ubah&nim=<?php echo $nim ?>"><button type="button" 
                                    class="btn btn-warning">Edit</button></a><a href="index.php?op=hapus&nim=
                                    <?php echo $nim ?>" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')">
                                    <button type="button" class="btn btn-danger">Hapus</button></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
