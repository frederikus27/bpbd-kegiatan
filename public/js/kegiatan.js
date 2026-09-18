/**
 * JavaScript untuk Website Kegiatan BPBD DIY
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Inisialisasi Modal Lightbox Foto
    const photoModal = document.getElementById('photoModal');
    if (photoModal) {
        photoModal.addEventListener('show.bs.modal', function (event) {
            const triggerEl = event.relatedTarget;
            const imgUrl = triggerEl.getAttribute('data-img-url');
            const kegTitle = triggerEl.getAttribute('data-kegiatan');
            const kegDate = triggerEl.getAttribute('data-tanggal');

            const modalImg = photoModal.querySelector('#modalImage');
            const modalTitle = photoModal.querySelector('#modalTitle');
            const modalDate = photoModal.querySelector('#modalDate');

            if (modalImg) {
                modalImg.src = imgUrl;
                modalImg.alt = kegTitle || 'Foto Kegiatan BPBD DIY';
            }
            if (modalTitle) {
                modalTitle.textContent = kegTitle || '';
            }
            if (modalDate) {
                modalDate.textContent = kegDate ? ('Tanggal: ' + kegDate) : '';
            }
        });
    }

    // 2. Tombol Cetak
    const btnCetak = document.getElementById('btnCetak');
    if (btnCetak) {
        btnCetak.addEventListener('click', function () {
            window.print();
        });
    }

    // 3. Konfirmasi Hapus Data
    const deleteForms = document.querySelectorAll('.form-delete-kegiatan');
    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const confirmed = confirm('Apakah Anda yakin ingin menghapus data kegiatan ini? Tindakan ini tidak dapat dibatalkan.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // 4. Auto-dismiss Alert setelah 5 detik
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 5000);
    });
});
