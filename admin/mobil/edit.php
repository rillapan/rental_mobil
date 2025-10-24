<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require '../../koneksi/koneksi.php';
    $title_web = 'Edit Mobil';
    include '../header.php';

    $id = $_GET['id'];

    $sql = "SELECT * FROM mobil WHERE id_mobil = ?";
    $row = $koneksi->prepare($sql);
    $row->execute(array($id));

    $hasil = $row->fetch();
    if(!$hasil){
        echo '<script>alert("Mobil tidak ditemukan !");window.location="mobil.php";</script>';
        exit;
    }

    $sql_gambar = "SELECT * FROM mobil_gambar WHERE id_mobil = ?";
    $row_gambar = $koneksi->prepare($sql_gambar);
    $row_gambar->execute(array($id));
    $hasil_gambar = $row_gambar->fetchAll(PDO::FETCH_OBJ);
?>
<style>
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-secondary:hover {
        background-color: #e55e2d;
        border-color: #e55e2d;
    }
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }
     :root {
        --primary: #1A237E; 
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
      }

      .card-header {
        background-color: var(--primary);
        color: white;
      }
      .image-card.deleted {
          opacity: 0.5;
          border: 2px dashed red;
      }
</style>
<div class="container my-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-edit me-2"></i> Edit Mobil - <?= htmlspecialchars($hasil['merk']); ?>
            </h5>
            <a href="mobil.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="post" action="proses.php?aksi=edit&id=<?= htmlspecialchars($id); ?>" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_plat" class="form-label">No Plat</label>
                            <input type="text" class="form-control" id="no_plat" value="<?= htmlspecialchars($hasil['no_plat']); ?>" name="no_plat" placeholder="Isi No Plat" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="merk" class="form-label">Merk Mobil</label>
                            <input type="text" class="form-control" id="merk" value="<?= htmlspecialchars($hasil['merk']); ?>" name="merk" placeholder="Isi Merk" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" value="<?= htmlspecialchars($hasil['harga']); ?>" name="harga" placeholder="Isi Harga" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Isi Deskripsi" required><?= htmlspecialchars($hasil['deskripsi']); ?></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Keunggulan Paket</label>
                            <div id="keunggulan-container">
                                <?php 
                                    $keunggulan = explode('||', $hasil['keunggulan']);
                                    $no = 1;
                                    foreach($keunggulan as $item){
                                        if(!empty($item)){
                                ?>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><?= $no;?>.</span>
                                    <input type="text" class="form-control" name="keunggulan[]" value="<?= htmlspecialchars($item);?>" placeholder="Contoh: Bensin gratis" required>
                                    <button type="button" class="btn btn-danger remove-keunggulan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <?php $no++;}}
                                if(empty($hasil['keunggulan'])){
                                ?>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">1.</span>
                                    <input type="text" class="form-control" name="keunggulan[]" placeholder="Contoh: Bensin gratis" required>
                                    <button type="button" class="btn btn-danger remove-keunggulan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <?php } ?>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-keunggulan">
                                <i class="fas fa-plus"></i> Tambah Keunggulan
                            </button>
                        </div>
                        <div class="form-group mt-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" disabled>Pilih Status</option>
                                <option value="Tersedia" <?php if($hasil['status'] == 'Tersedia'){ echo 'selected';}?>>Tersedia</option>
                                <option value="Tidak Tersedia" <?php if($hasil['status'] == 'Tidak Tersedia'){ echo 'selected';}?>>Tidak Tersedia</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="gambar" class="form-label">Gambar Utama</label>
                            <input type="file" accept="image/*" class="form-control" id="gambar" name="gambar">
                            <input type="hidden" value="<?= htmlspecialchars($hasil['gambar']); ?>" name="gambar_cek">
                            <div class="form-text">Abaikan jika tidak ingin mengubah gambar utama.</div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center mt-4">
                        <label class="form-label">Gambar Utama Saat Ini</label><br>
                        <img src="../../assets/image/<?= htmlspecialchars($hasil['gambar']); ?>" class="img-fluid rounded shadow-sm" style="max-width: 300px;" alt="Gambar Mobil">
                    </div>
                    <div class="col-md-12 mt-4">
                        <label class="form-label">Gambar Tambahan Saat Ini</label>
                        <div class="row" id="existing-gambar-tambahan-container">
                            <?php foreach($hasil_gambar as $gambar): ?>
                                <div class="col-md-3 col-sm-4 col-6 mb-3 image-card" id="image-card-<?= $gambar->id_gambar; ?>">
                                    <div class="card">
                                        <img src="../../assets/image/<?= htmlspecialchars($gambar->nama_gambar); ?>" class="card-img-top" style="height:150px;object-fit:cover;">
                                        <div class="card-body text-center">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input delete-gambar-checkbox" name="hapus_gambar[]" value="<?= $gambar->id_gambar; ?>">
                                                Hapus
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mt-3">
                            <label class="form-label">Tambah Gambar Tambahan Baru</label>
                            <div id="gambar-tambahan-container-new">
                                <!-- Dynamic image inputs will be added here -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-gambar-tambahan-new">
                                <i class="fas fa-plus"></i> Tambah Gambar Tambahan Baru
                            </button>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const keunggulanContainer = document.getElementById('keunggulan-container');
    const addKeunggulanButton = document.getElementById('add-keunggulan');

    const updateKeunggulanNumbering = () => {
        const advantages = keunggulanContainer.querySelectorAll('.input-group-text');
        advantages.forEach((adv, index) => {
            adv.textContent = `${index + 1}.`;
        });
    }

    const addAdvantageInput = (value = '') => {
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-2');
        inputGroup.innerHTML = `
            <span class="input-group-text">${keunggulanContainer.children.length + 1}.</span>
            <input type="text" class="form-control" name="keunggulan[]" value="${value}" placeholder="Contoh: Bensin gratis" required>
            <button type="button" class="btn btn-danger remove-keunggulan">
                <i class="fas fa-times"></i>
            </button>
        `;
        keunggulanContainer.appendChild(inputGroup);
        updateKeunggulanNumbering();
    }

    addKeunggulanButton.addEventListener('click', () => addAdvantageInput());

    keunggulanContainer.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-keunggulan');
        if (removeButton) {
            if (keunggulanContainer.children.length > 1) {
                removeButton.closest('.input-group').remove();
                updateKeunggulanNumbering();
            } else {
                alert('Setidaknya harus ada satu keunggulan.');
            }
        }
    });

    // Dynamic image upload fields for new images
    const newGambarTambahanContainer = document.getElementById('gambar-tambahan-container-new');
    const addGambarTambahanNewButton = document.getElementById('add-gambar-tambahan-new');

    const addImageInput = () => {
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-2');
        inputGroup.innerHTML = `
            <input type="file" accept="image/*" class="form-control" name="gambar_tambahan[]" required>
            <button type="button" class="btn btn-danger remove-gambar-tambahan-new">
                <i class="fas fa-times"></i>
            </button>
        `;
        newGambarTambahanContainer.appendChild(inputGroup);
    };

    addGambarTambahanNewButton.addEventListener('click', addImageInput);

    newGambarTambahanContainer.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-gambar-tambahan-new');
        if (removeButton) {
            removeButton.closest('.input-group').remove();
        }
    });

    // Add one new image input on page load
    addImageInput();

    // Handle existing image deletion checkboxes
    const existingGambarTambahanContainer = document.getElementById('existing-gambar-tambahan-container');
    existingGambarTambahanContainer.addEventListener('change', function(e) {
        const checkbox = e.target.closest('.delete-gambar-checkbox');
        if (checkbox) {
            const imageCard = checkbox.closest('.image-card');
            if (checkbox.checked) {
                imageCard.classList.add('deleted');
            } else {
                imageCard.classList.remove('deleted');
            }
        }
    });
});
</script>
<?php include '../footer.php';?>