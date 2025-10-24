<?php

    require '../../koneksi/koneksi.php';
    $title_web = 'Tambah Mobil';
    include '../header.php';
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
        background-color: var(--primary);
        border-color: var(--primary);
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
</style>
<div class="container my-4">
    <div class="card shadow-lg">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-plus-circle me-2"></i> Tambah Mobil
            </h5>
            <a href="mobil.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="post" action="proses.php?aksi=tambah" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_plat" class="form-label">No Plat</label>
                            <input type="text" class="form-control" id="no_plat" name="no_plat" placeholder="Contoh: B 1234 ABC" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="merk" class="form-label">Merk Mobil</label>
                            <input type="text" class="form-control" id="merk" name="merk" placeholder="Contoh: Toyota Avanza" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="harga" class="form-label">Harga Sewa per Hari</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 300000" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi singkat mobil" required></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Keunggulan Paket</label>
                            <div id="keunggulan-container">
                                <!-- Dynamic inputs will be added here -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-keunggulan">
                                <i class="fas fa-plus"></i> Tambah Keunggulan
                            </button>
                        </div>
                        <div class="form-group mt-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Tidak Tersedia">Tidak Tersedia</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="gambar" class="form-label">Gambar Mobil</label>
                            <input type="file" accept="image/*" class="form-control" id="gambar" name="gambar" required>
                            <div class="form-text">Pilih gambar dengan format JPG, PNG, atau GIF.</div>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Gambar Tambahan</label>
                            <div id="gambar-tambahan-container">
                                <!-- Dynamic image inputs will be added here -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-gambar-tambahan">
                                <i class="fas fa-plus"></i> Tambah Gambar Tambahan
                            </button>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('keunggulan-container');
    const addButton = document.getElementById('add-keunggulan');

    const addAdvantageInput = () => {
        const advantageCount = container.children.length + 1;
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-2');
        inputGroup.innerHTML = `
            <span class="input-group-text">${advantageCount}.</span>
            <input type="text" class="form-control" name="keunggulan[]" placeholder="Contoh: Bensin gratis" required>
            <button type="button" class="btn btn-danger remove-keunggulan">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(inputGroup);
    }

    addButton.addEventListener('click', addAdvantageInput);

    container.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-keunggulan');
        if (removeButton) {
            removeButton.closest('.input-group').remove();
            // Re-number advantages
            const advantages = container.querySelectorAll('.input-group-text');
            advantages.forEach((adv, index) => {
                adv.textContent = `${index + 1}.`;
            });
        }
    });

    // Add one input on page load
    addAdvantageInput();

    const gambarTambahanContainer = document.getElementById('gambar-tambahan-container');
    const addGambarTambahanButton = document.getElementById('add-gambar-tambahan');

    const addImageInput = () => {
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-2');
        inputGroup.innerHTML = `
            <input type="file" accept="image/*" class="form-control" name="gambar_tambahan[]" required>
            <button type="button" class="btn btn-danger remove-gambar-tambahan">
                <i class="fas fa-times"></i>
            </button>
        `;
        gambarTambahanContainer.appendChild(inputGroup);
    };

    addGambarTambahanButton.addEventListener('click', addImageInput);

    gambarTambahanContainer.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-gambar-tambahan');
        if (removeButton) {
            removeButton.closest('.input-group').remove();
        }
    });

    // Add one image input on page load
    addImageInput();
});
</script>

<?php include '../footer.php';?>