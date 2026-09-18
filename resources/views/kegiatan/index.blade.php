@extends('layouts.app')

@section('title', 'Dokumentasi Kegiatan BPBD DIY')

@section('content')
<div class="document-container">

    <!-- Kop Judul Resmi Dokumen -->
    <div class="doc-header text-center">
        <h1 class="doc-title">KEGIATAN BPBD DIY</h1>
        <h2 class="doc-subtitle">{{ $periodeJudul }}</h2>
    </div>

    <!-- Toolbar: Search, Filter, Tombol Aksi (no-print) -->
    <div class="filter-card no-print">
        <form action="{{ route('kegiatan.index') }}" method="GET" class="row g-3 align-items-end">
            <!-- Search Bar -->
            <div class="col-md-3 col-sm-12">
                <label for="search" class="form-label fw-semibold small text-secondary">
                    <i class="bi bi-search me-1"></i> Pencarian Kegiatan
                </label>
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari kegiatan...">
                    @if(request('search'))
                        <a href="{{ route('kegiatan.index', request()->except('search')) }}" class="btn btn-outline-secondary" title="Hapus teks pencarian">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Filter Bulan -->
            <div class="col-md-3 col-sm-6">
                <label for="bulan" class="form-label fw-semibold small text-secondary">
                    <i class="bi bi-calendar3 me-1"></i> Bulan
                </label>
                <select class="form-select" id="bulan" name="bulan">
                    <option value="">[ Semua Bulan ▼ ]</option>
                    @php
                        $bulanList = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ (string)request('bulan') === (string)$num ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tahun -->
            <div class="col-md-2 col-sm-6">
                <label for="tahun" class="form-label fw-semibold small text-secondary">
                    <i class="bi bi-calendar-event me-1"></i> Tahun
                </label>
                <select class="form-select" id="tahun" name="tahun">
                    <option value="">[ Semua Tahun ▼ ]</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ (string)request('tahun') === (string)$year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Cari, Reset, Cetak -->
            <div class="col-md-4 col-sm-12 d-flex gap-2 flex-wrap justify-content-md-end">
                <button type="submit" class="btn btn-bpbd px-3">
                    <i class="bi bi-funnel-fill me-1"></i> Cari
                </button>
                <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-3">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
                <button type="button" onclick="window.print()" class="btn btn-success px-3" title="Cetak tampilan kegiatan saat ini">
                    <i class="bi bi-printer-fill me-1"></i> Cetak
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Kegiatan Formal -->
    <div class="table-responsive">
        <table class="table-kegiatan">
            <thead>
                <tr>
                    <th class="col-no-tgl">NO</th>
                    <th class="col-hari-tgl">HARI/TANGGAL</th>
                    <th class="col-no-keg">NO</th>
                    <th class="col-kegiatan">KEGIATAN</th>
                    <th class="col-foto">FOTO</th>
                    <th class="col-aksi no-print">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groupedKegiatans as $dateKey => $items)
                    @php
                        $totalItemsOnDate = count($items);
                        $dateNumber = $dateOffset + $loop->iteration;
                    @endphp

                    @foreach($items as $itemIndex => $item)
                        <tr>
                            {{-- Kolom NO dan HARI/TANGGAL dengan rowspan jika baris pertama untuk tanggal ini --}}
                            @if($loop->first)
                                <td rowspan="{{ $totalItemsOnDate }}" class="col-no-tgl align-middle text-center">
                                    {{ $dateNumber }}
                                </td>
                                <td rowspan="{{ $totalItemsOnDate }}" class="col-hari-tgl align-middle text-center">
                                    {{ $item->tanggal_formatted }}
                                </td>
                            @endif

                            {{-- Kolom NO kegiatan: nomor urut dimulai dari 1 untuk setiap tanggal --}}
                            <td class="col-no-keg align-middle text-center">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Kolom Deskripsi Kegiatan --}}
                            <td class="col-kegiatan">
                                {{ $item->kegiatan }}
                            </td>

                            {{-- Kolom Foto Kegiatan --}}
                            <td class="col-foto">
                                @if($item->foto && file_exists(public_path('storage/' . $item->foto)))
                                    <div class="foto-thumbnail-container"
                                         data-bs-toggle="modal"
                                         data-bs-target="#photoModal"
                                         data-img-url="{{ asset('storage/' . $item->foto) }}"
                                         data-kegiatan="{{ $item->kegiatan }}"
                                         data-tanggal="{{ $item->tanggal_formatted }}"
                                         title="Klik untuk memperbesar foto">
                                        <img src="{{ asset('storage/' . $item->foto) }}"
                                             alt="Foto Kegiatan BPBD DIY"
                                             class="foto-kegiatan">
                                    </div>
                                @elseif($item->foto)
                                    <div class="foto-thumbnail-container"
                                         data-bs-toggle="modal"
                                         data-bs-target="#photoModal"
                                         data-img-url="{{ asset('storage/' . $item->foto) }}"
                                         data-kegiatan="{{ $item->kegiatan }}"
                                         data-tanggal="{{ $item->tanggal_formatted }}"
                                         title="Klik untuk memperbesar foto">
                                        <img src="{{ asset('storage/' . $item->foto) }}"
                                             alt="Foto Kegiatan BPBD DIY"
                                             class="foto-kegiatan">
                                    </div>
                                @else
                                    <span class="foto-empty">Belum ada foto</span>
                                @endif
                            </td>

                            {{-- Kolom Aksi Admin (no-print) --}}
                            <td class="col-aksi no-print">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('kegiatan.edit', $item->id) }}"
                                       class="btn btn-outline-primary"
                                       title="Edit Kegiatan">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('kegiatan.destroy', $item->id) }}"
                                          method="POST"
                                          class="d-inline form-delete-kegiatan">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-outline-danger"
                                                title="Hapus Kegiatan">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary"></i>
                            <span class="fw-semibold">Tidak ada data kegiatan yang ditemukan.</span>
                            <br>
                            <small>Coba gunakan kata kunci pencarian atau filter bulan/tahun yang berbeda.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Informasi Pagination & Navigasi Halaman (no-print) -->
    <div class="pagination-container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4 pt-3 border-top no-print">
        <div class="text-muted small">
            @if($kegiatans->total() > 0)
                Menampilkan <strong>{{ $kegiatans->firstItem() }}</strong>-<strong>{{ $kegiatans->lastItem() }}</strong> dari <strong>{{ $kegiatans->total() }}</strong> kegiatan
            @else
                Menampilkan 0 kegiatan
            @endif
        </div>
        <div>
            {{ $kegiatans->links() }}
        </div>
    </div>

</div>
@endsection