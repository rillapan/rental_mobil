<?php
    require '../../koneksi/koneksi.php';
    $title_web = 'Daftar Booking';
    $url = '../../';
    include '../header.php';

    $filter_status = $_GET['status'] ?? '';
    $where_clause = '';
    $params = [];

    if (!empty($filter_status) && $filter_status != 'Semua Status') {
        $where_clause = ' WHERE booking.konfirmasi_pembayaran = ?';
        $params[] = $filter_status;
    }

    if(!empty($_GET['id'])){
        $id = strip_tags($_GET['id']);
        if (!empty($where_clause)) {
            $where_clause .= ' AND id_login = ?';
        } else {
            $where_clause .= ' WHERE id_login = ?';
        }
        $params[] = $id;
    }

    $sql = "SELECT mobil.merk, booking.* FROM booking JOIN mobil ON 
            booking.id_mobil=mobil.id_mobil" . $where_clause . " ORDER BY id_booking DESC";
    
    $row = $koneksi->prepare($sql);
    $row->execute($params);
    $hasil = $row->fetchAll();
?>

<br>
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-check me-2"></i> Daftar Booking
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="booking.php" id="filterForm">
                    <div class="row align-items-center">
                     <form id="filterForm" method="get" action="">
    <div class="row g-2 align-items-center">
        <div class="col-auto">
            <label for="filterStatus" class="col-form-label"><i class="fas fa-filter me-1"></i> Filter Status:</label>
        </div>
        <div class="col-auto">
            <select class="form-select" id="filterStatus" name="status" onchange="this.form.submit();">
                <option value="" <?= (empty($_GET['status'])) ? 'selected' : ''; ?>>Semua Status</option>
                <option value="Pembayaran Diterima" <?= (isset($_GET['status']) && $_GET['status'] == 'Pembayaran Diterima') ? 'selected' : ''; ?>>Pembayaran Diterima</option>
                                <option value="Sedang Diproses" <?= (isset($_GET['status']) && $_GET['status'] == 'Sedang Diproses') ? 'selected' : ''; ?>>Sedang Diproses</option>
                <option value="Sudah Dibayar" <?= (isset($_GET['status']) && $_GET['status'] == 'Sudah Dibayar') ? 'selected' : ''; ?>>Sudah Dibayar</option>
                <option value="Belum Dibayar" <?= (isset($_GET['status']) && $_GET['status'] == 'Belum Dibayar') ? 'selected' : ''; ?>>Belum Dibayar</option>
            </select>
        </div>
    </div>
</form>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col">Kode Booking</th>
                            <th scope="col">Merk Mobil</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Tanggal Sewa</th>
                            <th scope="col" class="text-center">Lama Sewa</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col" class="text-center">Konfirmasi</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no = 1; 
                            foreach($hasil as $isi) {
                        ?>
                        <tr>
                            <td class="text-center align-middle"><?= $no;?></td>
                            <td class="align-middle"><?= htmlspecialchars($isi['kode_booking']);?></td>
                            <td class="align-middle"><?= htmlspecialchars($isi['merk']);?></td>
                            <td class="align-middle"><?= htmlspecialchars($isi['nama']);?></td>
                            <td class="align-middle"><?= date('d/m/Y', strtotime($isi['tanggal']));?></td>
                            <td class="text-center align-middle"><?= htmlspecialchars($isi['lama_sewa']);?> hari</td>
                            <td class="align-middle">Rp<?= number_format(htmlspecialchars($isi['total_harga']), 0, ',', '.');?></td>
                            <td class="text-center align-middle">
                                <?php if($isi['konfirmasi_pembayaran'] == 'Pembayaran Diterima'){ ?>
                                    <span class="badge bg-success text-white"><i class="fas fa-check-circle me-1"></i> Pembayaran Diterima</span>
                                <?php } elseif($isi['konfirmasi_pembayaran'] == 'Sedang Diproses'){ ?>
                                    <span class="badge bg-warning text-white"><i class="fas fa-hourglass-half me-1"></i> Diproses</span>
                                <?php } elseif($isi['konfirmasi_pembayaran'] == 'Sudah Dibayar'){ ?>
                                    <span class="badge bg-info text-white"><i class="fas fa-info-circle me-1"></i> Selesai</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger text-white"><i class="fas fa-times-circle me-1"></i> Belum Dibayar</span>
                                <?php } ?>
                            </td>
                            <td class="text-center align-middle">
                                <a class="btn btn-secondary btn-sm" href="bayar.php?id=<?= $isi['kode_booking'];?>" 
                                   role="button" title="Lihat Detail Transaksi">
                                   <i class="fas fa-info-circle"></i> Detail
                                </a> 
                            </td>
                        </tr>
                        <?php $no++;}?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Menggunakan tema warna dari file sebelumnya */
    :root {
        --primary: #1A237E; 
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
    }
    .bg-primary {
        background-color: var(--primary) !important;
    }
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
        color: white;
    }
    .btn-secondary:hover {
        background-color: #e55e2d;
        border-color: #e55e2d;
    }
    .badge {
        /* Ensure text is white and centered within the badge */
        color: white !important;
        text-align: center;
        display: inline-flex; /* Use flexbox for better vertical alignment of icon and text */
        align-items: center;
        justify-content: center;
        padding: .5em .75em; /* Slightly more padding */
        font-size: 95%; /* Slightly larger font */
        border-radius: .3rem; /* Slightly more rounded corners */
    }
    .badge i {
        margin-right: .3em; /* Space between icon and text */
    }
</style>
<?php  include '../footer.php';?>