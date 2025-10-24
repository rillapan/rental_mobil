<?php

    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
?>
<style>
/* Custom CSS untuk desain Kontak Kami yang modern */
:root {
    --primary: #1A237E; 
    --primary-dark: #1A3CC9;
    --secondary: #FF6B35;
    --light: #F8F9FA;
    --dark: #212529;
    --gray: #6C757D;
}

.contact-card {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.card-header.contact-header {
    background-color: var(--primary);
    color: #fff;
    padding: 20px;
    font-size: 1.5rem;
    font-weight: 600;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    text-align: center;
}

.contact-body {
    padding: 30px;
}

.contact-item {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.contact-item .icon {
    font-size: 1.5rem;
    color: var(--primary);
    width: 40px;
    text-align: center;
    margin-right: 15px;
}

.contact-item .label {
    font-weight: bold;
    color: var(--gray);
    flex: 0 0 120px;
}

.contact-item .info {
    font-weight: 500;
    color: var(--dark);
}

.contact-buttons a {
    width: 100%;
    margin-bottom: 10px;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    transition: all 0.3s ease;
}

.btn-outline-primary {
    color: var(--primary);
    border-color: var(--primary);
}

.btn-outline-primary:hover {
    background-color: var(--primary);
    color: white;
}

.btn-outline-info {
    color: var(--secondary);
    border-color: var(--secondary);
}

.btn-outline-info:hover {
    background-color: var(--secondary);
    color: white;
}

/* Kurangi jarak atas container */
.container.py-5 {
    padding-top: 24px !important;
}
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-sm-10 col-md-10 mx-auto">
            <div class="card contact-card">
                <div class="card-header contact-header">
                    Hubungi Kami
                </div>
                <div class="card-body contact-body">
                    <div class="contact-item">
                        <div class="icon"><i class="fas fa-building"></i></div>
                        <div class="info">
                            <div class="label">Nama Rental</div>
                            <div><?= $info_web->nama_rental;?></div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="icon"><i class="fas fa-phone"></i></div>
                        <div class="info">
                            <div class="label">Telepon</div>
                            <div><?= $info_web->telp;?></div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="info">
                            <div class="label">Alamat</div>
                            <div><?= $info_web->alamat;?></div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="icon"><i class="fas fa-envelope"></i></div>
                        <div class="info">
                            <div class="label">Email</div>
                            <div><?= $info_web->email;?></div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="icon"><i class="fas fa-credit-card"></i></div>
                        <div class="info">
                            <div class="label">No Rekening</div>
                            <div><?= $info_web->no_rek;?></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="contact-buttons text-center">
                        <a href="tel:<?= $info_web->telp;?>" class="btn btn-outline-primary btn-lg"><i class="fas fa-phone"></i> Hubungi via Telepon</a>
                        <a href="mailto:<?= $info_web->email;?>" class="btn btn-outline-info btn-lg"><i class="fas fa-envelope"></i> Kirim Email</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>
<?php include 'footer.php';?>