<?php
session_start();
require 'koneksi/koneksi.php';
require 'header.php';

if (!isset($_SESSION['USER'])) {
    echo '<script>alert("Anda harus login untuk melihat notifikasi.");window.location="login.php";</script>';
    exit();
}

$id_login_user = $_SESSION['USER']['id_login'];

// Tandai semua notifikasi yang belum dibaca sebagai sudah dibaca
$stmt_mark_read = $koneksi->prepare("UPDATE notifikasi SET status_baca = 1 WHERE id_login = ? AND status_baca = 0");
$stmt_mark_read->execute([$id_login_user]);

// Ambil semua notifikasi untuk user yang sedang login
$stmt_notifikasi = $koneksi->prepare("SELECT * FROM notifikasi WHERE id_login = ? ORDER BY created_at DESC");
$stmt_notifikasi->execute([$id_login_user]);
$notifikasi = $stmt_notifikasi->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-bell"></i> Notifikasi Anda</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($notifikasi)) { ?>
                        <div class="alert alert-info text-center" role="alert">
                            Tidak ada notifikasi untuk Anda saat ini.
                        </div>
                    <?php } else { ?>
                        <ul class="list-group">
                            <?php foreach ($notifikasi as $notif) { ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center <?php echo ($notif['status_baca'] == 0) ? 'list-group-item-light' : ''; ?>">
                                    <div>
                                        <div class="mb-1"><?= $notif['pesan']; ?></div>
                                        <small class="text-muted"><i class="far fa-clock"></i> <?= date('d M Y, H:i', strtotime($notif['created_at'])); ?></small>
                                    </div>
                                    <?php if ($notif['status_baca'] == 0) { ?>
                                        <span class="badge badge-primary badge-pill">Baru</span>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>