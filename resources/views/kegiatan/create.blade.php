@extends('layouts.app')

@section('title', 'Tambah Kegiatan Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-plus-circle-dotted me-2"></i> Tambah Kegiatan BPBD DIY
                </h5>
                <a href="{{ route('kegiatan.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Field Tanggal -->
                    <div class="mb-3">
                        <label for="tanggal" class="form-label fw-bold">
                            Hari / Tanggal <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               id="tanggal"
                               name="tanggal"
                               value="{{ old('tanggal', date('Y-m-d')) }}"
                               required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Field Deskripsi Kegiatan -->
                    <div class="mb-3">
                        <label for="kegiatan" class="form-label fw-bold">
                            Deskripsi Kegiatan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('kegiatan') is-invalid @enderror"
                                  id="kegiatan"
                                  name="kegiatan"
                                  rows="4"
                                  placeholder="Masukkan deskripsi lengkap kegiatan..."
                                  required>{{ old('kegiatan') }}</textarea>
                        @error('kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notifikasi Proteksi Duplikasi -->
                    <div class="alert alert-light border small text-muted d-flex align-items-center mb-3">
                        <i class="bi bi-shield-check text-primary fs-5 me-2"></i>
                        <span><strong>Proteksi Duplikasi Aktif:</strong> Sistem memastikan kegiatan dengan tanggal dan deskripsi yang sama tidak dapat di-upload kembali.</span>
                    </div>

                    <!-- Field Foto Dokumentasi -->
                    <div class="mb-4">
                        <label for="foto" class="form-label fw-bold">
                            Foto Kegiatan <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <input type="file"
                               class="form-control @error('foto') is-invalid @enderror"
                               id="foto"
                               name="foto"
                               accept="image/png, image/jpeg, image/jpg"
                               onchange="previewImage(this)">
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">
                            Format yang didukung: JPG, JPEG, PNG. Ukuran maksimal: 2 MB (2048 KB).
                        </div>

                        <!-- Image Preview -->
                        <div class="mt-3 d-none" id="previewContainer">
                            <p class="small fw-semibold text-secondary mb-1">Pratinjau Foto yang Dipilih:</p>
                            <img id="imagePreview" src="#" alt="Pratinjau Foto" class="rounded border p-1" style="max-height: 180px; max-width: 250px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-4">
                            Batal
                        </a>
                        <button type="submit" id="btnSubmitKegiatan" class="btn btn-primary px-4 fw-bold">
                            <i class="bi bi-save me-1"></i> Simpan Kegiatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.classList.add('d-none');
        }
    }

    // Mencegah double submit / upload ganda tidak sengaja
    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmitKegiatan');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
        }
    });
</script>
@endpush
