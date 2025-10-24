<?php

require '../../koneksi/koneksi.php';

// Start the session if it hasn't been started yet.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['USER'])) {
    echo '<script>alert("Login Dulu !");window.location="../index.php";</script>';
    exit();
}

$aksi = $_GET['aksi'] ?? '';

if ($aksi == 'tambah') {

    $allowedTypes = [
        'image/png'   => 'png',
        'image/jpeg'  => 'jpg',
        'image/gif'   => 'gif',
        'image/webp'  => 'webp'
    ];
    if (empty($_FILES['gambar']['name'])) {
        header("location: mobil.php?pesan=gagal-tambah");
        exit();
    }
    $filepath = $_FILES['gambar']['tmp_name'];
    $fileSize = filesize($filepath);
    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    $filetype = finfo_file($fileinfo, $filepath);

    if (!in_array($filetype, array_keys($allowedTypes))) {
        header("location: mobil.php?pesan=gagal-tambah");
        exit();
    } elseif ($_FILES['gambar']["error"] > 0) {
        header("location: mobil.php?pesan=gagal-tambah");
        exit();
    } elseif (round($_FILES['gambar']["size"] / 1024) > 4096) {
        header("location: mobil.php?pesan=gagal-tambah");
        exit();
    } else {
        $dir = '../../assets/image/';
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $temp = explode(".", $_FILES["gambar"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $target_path = $dir . basename($newfilename);
        if (move_uploaded_file($tmp_name, $target_path)) {
            
            $keunggulan = isset($_POST['keunggulan']) ? array_filter($_POST['keunggulan']) : [];
            $keunggulan_string = implode('||', $keunggulan);

            $data[] = $_POST['no_plat'];
            $data[] = $_POST['merk'];
            $data[] = $_POST['harga'];
            $data[] = $_POST['deskripsi'];
            $data[] = $keunggulan_string;
            $data[] = $_POST['status'];
            $data[] = $newfilename;

            $sql = "INSERT INTO `mobil`(`no_plat`, `merk`, `harga`, `deskripsi`, `keunggulan`, `status`, `gambar`) 
                VALUES (?,?,?,?,?,?,?)";
            $row = $koneksi->prepare($sql);
            $row->execute($data);

            $id_mobil = $koneksi->lastInsertId();

            if (isset($_FILES['gambar_tambahan'])) {
                foreach ($_FILES['gambar_tambahan']['tmp_name'] as $key => $tmp_name) {
                    if (!empty($tmp_name)) {
                        $filepath_tambahan = $_FILES['gambar_tambahan']['tmp_name'][$key];
                        $fileinfo_tambahan = finfo_open(FILEINFO_MIME_TYPE);
                        $filetype_tambahan = finfo_file($fileinfo_tambahan, $filepath_tambahan);

                        if (in_array($filetype_tambahan, array_keys($allowedTypes)) && $_FILES['gambar_tambahan']["error"][$key] == 0 && round($_FILES['gambar_tambahan']["size"][$key] / 1024) <= 4096) {
                            $temp_tambahan = explode(".", $_FILES["gambar_tambahan"]["name"][$key]);
                            $newfilename_tambahan = round(microtime(true)) . '_' . $key . '.' . end($temp_tambahan);
                            $target_path_tambahan = $dir . basename($newfilename_tambahan);

                            if (move_uploaded_file($filepath_tambahan, $target_path_tambahan)) {
                                $sql_gambar = "INSERT INTO mobil_gambar (id_mobil, nama_gambar) VALUES (?, ?)";
                                $stmt_gambar = $koneksi->prepare($sql_gambar);
                                $stmt_gambar->execute([$id_mobil, $newfilename_tambahan]);
                            }
                        }
                    }
                }
            }

            header("location: mobil.php?pesan=sukses-tambah");
            exit();
        } else {
            header("location: mobil.php?pesan=gagal-tambah");
            exit();
        }
    }

} elseif ($aksi == 'edit') {

    $id = $_GET['id'];

    // Handle deletion of additional images
    if (isset($_POST['hapus_gambar'])) {
        foreach ($_POST['hapus_gambar'] as $id_gambar) {
            $sql_select_gambar = "SELECT nama_gambar FROM mobil_gambar WHERE id_gambar = ?";
            $stmt_select_gambar = $koneksi->prepare($sql_select_gambar);
            $stmt_select_gambar->execute([$id_gambar]);
            $nama_gambar = $stmt_select_gambar->fetchColumn();

            if ($nama_gambar && file_exists('../../assets/image/' . $nama_gambar)) {
                unlink('../../assets/image/' . $nama_gambar);
            }

            $sql_delete_gambar = "DELETE FROM mobil_gambar WHERE id_gambar = ?";
            $stmt_delete_gambar = $koneksi->prepare($sql_delete_gambar);
            $stmt_delete_gambar->execute([$id_gambar]);
        }
    }

    // Handle upload of new additional images
    if (isset($_FILES['gambar_tambahan'])) {
        $allowedTypes = [
            'image/png'   => 'png',
            'image/jpeg'  => 'jpg',
            'image/gif'   => 'gif',
            'image/webp'  => 'webp'
        ];
        $dir = '../../assets/image/';

        foreach ($_FILES['gambar_tambahan']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) {
                $filepath_tambahan = $_FILES['gambar_tambahan']['tmp_name'][$key];
                $fileinfo_tambahan = finfo_open(FILEINFO_MIME_TYPE);
                $filetype_tambahan = finfo_file($fileinfo_tambahan, $filepath_tambahan);

                if (in_array($filetype_tambahan, array_keys($allowedTypes)) && $_FILES['gambar_tambahan']["error"][$key] == 0 && round($_FILES['gambar_tambahan']["size"][$key] / 1024) <= 4096) {
                    $temp_tambahan = explode(".", $_FILES["gambar_tambahan"]["name"][$key]);
                    $newfilename_tambahan = round(microtime(true)) . '_' . $key . '.' . end($temp_tambahan);
                    $target_path_tambahan = $dir . basename($newfilename_tambahan);

                    if (move_uploaded_file($filepath_tambahan, $target_path_tambahan)) {
                        $sql_gambar = "INSERT INTO mobil_gambar (id_mobil, nama_gambar) VALUES (?, ?)";
                        $stmt_gambar = $koneksi->prepare($sql_gambar);
                        $stmt_gambar->execute([$id, $newfilename_tambahan]);
                    }
                }
            }
        }
    }

    $gambar = $_POST['gambar_cek'];

    $keunggulan = isset($_POST['keunggulan']) ? array_filter($_POST['keunggulan']) : [];
    $keunggulan_string = implode('||', $keunggulan);

    $data[] = $_POST['no_plat'];
    $data[] = $_POST['merk'];
    $data[] = $_POST['harga'];
    $data[] = $_POST['deskripsi'];
    $data[] = $keunggulan_string;
    $data[] = $_POST['status'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['size'] > 0) {
        $filepath = $_FILES['gambar']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);
        $allowedTypes = [
            'image/png'   => 'png',
            'image/jpeg'  => 'jpg',
            'image/gif'   => 'gif',
            'image/webp'  => 'webp'
        ];
        if(!in_array($filetype, array_keys($allowedTypes))) {
            header("location: mobil.php?pesan=gagal-upload");
            exit();
        }else if ($_FILES['gambar']["error"] > 0) {
            header("location: mobil.php?pesan=gagal-upload");
            exit();
        } elseif (round($_FILES['gambar']["size"] / 1024) > 4096) {
            header("location: mobil.php?pesan=gagal-upload");
            exit();
        } else {
            $dir = '../../assets/image/';
            $tmp_name = $_FILES['gambar']['tmp_name'];
            $temp = explode(".", $_FILES["gambar"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $target_path = $dir . basename($newfilename);
            if (move_uploaded_file($tmp_name, $target_path)) {
                if (file_exists('../../assets/image/'.$gambar)) {
                    unlink('../../assets/image/'.$gambar);
                }
                $data[] = $newfilename;
            } else {
                header("location: mobil.php?pesan=gagal-upload");
                exit();
            }
        }
    } else {
        $data[] = $_POST['gambar_cek'];
    }

    $data[] = $id;
    $sql = "UPDATE mobil SET no_plat= ?, merk=?, harga=?, deskripsi=?, keunggulan=?, status=?, gambar=? WHERE id_mobil = ?";
    $row = $koneksi->prepare($sql);
    $success = $row->execute($data);

    if ($success) {
        header("location: mobil.php?pesan=sukses-edit");
        exit();
    } else {
        header("location: mobil.php?pesan=gagal-edit");
        exit();
    }

} elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    $gambar = $_GET['gambar'];

    $sql_check = "SELECT * FROM booking WHERE id_mobil = ?";
    $row_check = $koneksi->prepare($sql_check);
    $row_check->execute(array($id));
    $cek_booking = $row_check->rowCount();

    if ($cek_booking > 0) {
        header("location: mobil.php?pesan=gagal-hapus");
        exit();
    } else {
        if (file_exists('../../assets/image/'.$gambar)) {
            unlink('../../assets/image/'.$gambar);
        }
        $sql = "DELETE FROM mobil WHERE id_mobil = ?";
        $row = $koneksi->prepare($sql);
        $row->execute(array($id));
        header("location: mobil.php?pesan=sukses-hapus");
        exit();
    }
}