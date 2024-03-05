<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rumah_sakit";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

$rm ="";
$nik        = "";
$nama       = "";
$tanggal_lahir     = "";
$alamat   = "";
$nomor_bpjs     = "";
$jenis_kelamin      = "";
$tujuan_poly = "";
$sukses     = "";
$error      = "";

$query = mysqli_query($koneksi, "SELECT max(rm) as rekammedis FROM pasien");
$data = mysqli_fetch_array($query);
$koderm = $data['rekammedis'];


$urutan = (int) substr($koderm, 3, 3);
$urutan++;

$huruf = "RM";
$koderm = $huruf . sprintf("%03s", $urutan);


if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $id         = $_GET['id'];
    $sql1       = "delete from pasien where id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error  = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id         = $_GET['id'];
    $sql1       = "select * from pasien where id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $rm        = $r1['rm'];
    $nik        = $r1['nik'];
    $nama       = $r1['nama'];
    $tanggal_lahir = $r1['tanggal_lahir'];
    $alamat     = $r1['alamat'];
    $nomor_bpjs   = $r1['nomor_bpjs'];
    $jenis_kelamin   = $r1['jenis_kelamin'];
    $tujuan_poly = $r1['tujuan_poly'];

    if ($nik == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $rm = $_POST['rm'];
    $nik       = $_POST['nik'];
    $nama       = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $nomor_bpjs = $_POST['nomor_bpjs'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tujuan_poly = $_POST['tujuan_poly'];

    if ($nik && $nama && $tanggal_lahir && $alamat && $nomor_bpjs && $jenis_kelamin &&  $tujuan_poly) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update pasien set 
             rm = '$rm',
             nik = '$nik',
             nama='$nama',
             tanggal_lahir = '$tanggal_lahir', 
             alamat = '$alamat',
             nomor_bpjs ='$nomor_bpjs',
             jenis_kelamin = '$jenis_kelamin',
             tujuan_poly  = '$tujuan_poly'
             where id = '$id'";

            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1   = "insert into pasien (rm, nik,nama,tanggal_lahir,alamat, nomor_bpjs, jenis_kelamin, tujuan_poly) 
            values ('$rm', $nik','$nama','$tanggal_lahir', '$alamat','$nomor_bpjs', '$jenis_kelamin', '$tujuan_poly')";

            $q1     = mysqli_query($koneksi, $sql1);

            if ($q1) {
                $sukses     = "Berhasil memasukkan data baru";
            } else {
                $error      = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 1000px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php"); //5 : detik
                }
                ?>
                <?php

                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">

                <div class="mb-3 row">
                        <label for="rm" class="col-sm-2 col-form-label">RM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="rm" name="rm" value="<?php echo $koderm ?>">
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="nik" class="col-sm-2 col-form-label">NIk</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nik" name="nik" value="<?php echo $nik ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tanggal_lahir" class="col-sm-2 col-form-label">tanggal_lahir</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $tanggal_lahir ?>">
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="nomor_bpjs" class="col-sm-2 col-form-label">Nomor BPJS</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nomor_bpjs" name="nomor_bpjs" value="<?php echo $nomor_bpjs ?>">
                        </div>
                    </div>

                   


                    <div class="mb-3 row">
                        <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                <option value="">- Pilih Jenis Kelamin -</option>
                                <option value="laki-laki" <?php if ($jenis_kelamin == "laki-laki") echo "selected" ?>>Laki-Laki</option>
                                <option value="Perempuan" <?php if ($jenis_kelamin == "perempuan") echo "selected" ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tujuan_poly" class="col-sm-2 col-form-label">Tujuan Poly</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="tujuan_poly" id="tujuan_poly">
                                <option value="">- Pilih Tujuan Poly -</option>
                                <option value="Poly umum" <?php if ($tujuan_poly == "poly umum") echo "selected" ?>>Poly Umum </option>
                                <option value="poly gigi" <?php if ($tujuan_poly == "poly gigi") echo "selected" ?>>Poly Gigi</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Pasien
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">RM</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Tanggal Lahir</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Nomor BPJS</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col">Tujuan Poly</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "select * from pasien order by id desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id         = $r2['id'];
                            $rm         = $r2['rm'];
                            $nik        = $r2['nik'];
                            $nama       = $r2['nama'];
                            $tanggal_lahir = $r2['tanggal_lahir'];
                            $alamat     = $r2['alamat'];
                            $nomor_bpjs   = $r2['nomor_bpjs'];
                            $jenis_kelamin   = $r2['jenis_kelamin'];
                            $tujuan_poly = $r2['tujuan_poly'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $rm ?></td>
                                <td scope="row"><?php echo $nik ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $tanggal_lahir ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $nomor_bpjs ?></td>
                                <td scope="row"><?php echo $jenis_kelamin ?></td>
                                <td scope="row"><?php echo $tujuan_poly ?></td>
                                
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
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