@extends('layouts.app')

@section('title', 'Edit Kegiatan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square me-2"></i> Edit Data Kegiatan BPBD DIY
                </h5>
                <a href="{{ route('kegiatan.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Field Tanggal -->
                    <div class="mb-3">
                        <label for="tanggal" class="form-label fw-bold">
                            Hari / Tanggal <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               id="tanggal"
                               name="tanggal"
                               value="{{ old('tanggal', $kegiatan->tanggal ? $kegiatan->tanggal->format('Y-m-d') : '') }}"
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
                                  required>{{ old('kegiatan', $kegiatan->kegiatan) }}</textarea>
                        @error('kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Field Foto Dokumentasi -->
                    <div class="mb-4">
                        <label for="foto" class="form-label fw-bold">
                            Foto Kegiatan
                        </label>

                        <!-- Tampilan Foto Saat Ini -->
                        @if($kegiatan->foto)
                            <div class="mb-2 p-2 border rounded bg-light d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/' . $kegiatan->foto) }}"
                                     alt="Foto Kegiatan Saat Ini"
                                     class="rounded border"
                                     style="width: 120px; height: 80px; object-fit: cover;">
                                <div>
                                    <span class="badge bg-info text-dark mb-1">Foto Saat Ini</span>
                                    <div class="small text-muted">Upload file baru di bawah ini jika ingin mengganti foto ini.</div>
                                </div>
                            </div>
                        @endif

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
                            Format yang didukung: JPG, JPEG, PNG. Ukuran maksimal: 2 MB (2048 KB). Kosongkan jika tidak ingin mengubah foto.
                        </div>

                        <!-- Image Preview untuk Foto Baru -->
                        <div class="mt-3 d-none" id="previewContainer">
                            <p class="small fw-semibold text-secondary mb-1">Pratinjau Foto Baru yang Dipilih:</p>
                            <img id="imagePreview" src="#" alt="Pratinjau Foto Baru" class="rounded border p-1" style="max-height: 180px; max-width: 250px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Simpan Perubahan
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
</script>
@endpush
