<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kegiatan::query();

        // 1. Pencarian Cerdas (Kegiatan, Tanggal, Nama Bulan, Tahun, atau Tanggal Lengkap)
        if ($request->filled('search')) {
            $search = trim($request->search);

            $monthsMap = [
                'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
                'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
                'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
            ];

            $lowerSearch = strtolower($search);

            $query->where(function ($q) use ($search, $lowerSearch, $monthsMap) {
                // Selalu periksa apakah ada kecocokan pada teks kegiatan
                $q->where('kegiatan', 'like', "%{$search}%");

                // Coba deteksi apakah input adalah tanggal lengkap (contoh: "02 September 2026", "2-9-2026", "2026-09-02")
                $isFullDate = false;
                $englishMonthSearch = str_ireplace(
                    ['januari', 'februari', 'maret', 'mei', 'juni', 'juli', 'agustus', 'oktober', 'desember'],
                    ['january', 'february', 'march', 'may', 'june', 'july', 'august', 'october', 'december'],
                    $search
                );

                try {
                    // Jika terdapat angka (hari) dan kata bulan atau format tanggal
                    if (preg_match('/\b\d{1,2}\b/', $search) && (preg_match('/[a-zA-Z]/', $search) || preg_match('/[-\/]/', $search))) {
                        $parsed = Carbon::parse($englishMonthSearch);
                        if ($parsed && $parsed->year > 2000) {
                            $q->orWhereDate('tanggal', $parsed->format('Y-m-d'));
                            $isFullDate = true;
                        }
                    }
                } catch (\Exception $e) {
                    $isFullDate = false;
                }

                // Jika BUKAN tanggal lengkap, lakukan pencarian nama bulan, tahun, atau potongan tanggal
                if (!$isFullDate) {
                    $q->orWhere('tanggal', 'like', "%{$search}%");

                    // Cek pencarian nama bulan Bahasa Indonesia (misal: "September")
                    foreach ($monthsMap as $monthName => $monthNum) {
                        if (str_contains($lowerSearch, $monthName)) {
                            $q->orWhereMonth('tanggal', $monthNum);
                        }
                    }

                    // Cek pencarian angka tahun 4 digit (misal: "2026")
                    if (preg_match('/\b(20\d\d)\b/', $search, $yearMatches)) {
                        $q->orWhereYear('tanggal', $yearMatches[1]);
                    }
                }
            });
        }

        // 2. Filter Berdasarkan Bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', (int) $request->bulan);
        }

        // 3. Filter Berdasarkan Tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', (int) $request->tahun);
        }

        // 4. Urutkan secara konsisten (tanggal terlama ke terbaru)
        $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc');

        // 5. Pagination (10 data kegiatan per halaman) dengan mempertahankan query string
        $kegiatans = $query->paginate(10)->withQueryString();

        // 6. Sub-judul Periode Dinamis
        $bulanFilter = $request->bulan;
        $tahunFilter = $request->tahun;

        if ($bulanFilter && $tahunFilter) {
            $namaBulan = Carbon::create()->month((int) $bulanFilter)->locale('id')->translatedFormat('F');
            $periodeJudul = $namaBulan . ' ' . $tahunFilter;
        } elseif ($bulanFilter) {
            $namaBulan = Carbon::create()->month((int) $bulanFilter)->locale('id')->translatedFormat('F');
            $periodeJudul = 'Bulan ' . $namaBulan;
        } elseif ($tahunFilter) {
            $periodeJudul = 'Tahun ' . $tahunFilter;
        } else {
            // Ketika semua kegiatan ditampilkan
            $periodeJudul = 'Semua Kegiatan';
        }

        // 7. Ambil daftar tahun yang tersedia untuk filter dropdown (kompatibel MySQL & SQLite)
        if (config('database.default') === 'sqlite') {
            $availableYears = Kegiatan::selectRaw("strftime('%Y', tanggal) as year")
                ->whereNotNull('tanggal')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->filter()
                ->map(fn($y) => (int) $y)
                ->values()
                ->toArray();
        } else {
            $availableYears = Kegiatan::selectRaw('YEAR(tanggal) as year')
                ->whereNotNull('tanggal')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->filter()
                ->map(fn($y) => (int) $y)
                ->values()
                ->toArray();
        }

        if (empty($availableYears)) {
            $availableYears = [2026, 2025];
        }

        // 8. Kelompokkan item halaman aktif berdasarkan tanggal untuk perhitungan rowspan
        $groupedKegiatans = $kegiatans->getCollection()->groupBy(function ($item) {
            return $item->tanggal ? $item->tanggal->format('Y-m-d') : '';
        });

        // Hitung offset nomor tanggal untuk halaman pagination ini
        $dateOffset = 0;
        if ($kegiatans->currentPage() > 1) {
            $earlierItems = (clone $query)
                ->forPage(1, ($kegiatans->currentPage() - 1) * $kegiatans->perPage())
                ->get();
            $dateOffset = $earlierItems->groupBy(function ($item) {
                return $item->tanggal ? $item->tanggal->format('Y-m-d') : '';
            })->count();
        }

        return view('kegiatan.index', compact(
            'kegiatans',
            'groupedKegiatans',
            'periodeJudul',
            'availableYears',
            'dateOffset'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'tanggal.required' => 'Tanggal kegiatan wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'kegiatan.required' => 'Deskripsi kegiatan wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Pencegahan Duplikasi: Kegiatan yang sudah di-upload tidak bisa di-upload lagi
        $trimmedKegiatan = trim($validated['kegiatan']);
        $tanggalInput = $validated['tanggal'];

        $sudahAda = Kegiatan::whereDate('tanggal', $tanggalInput)
            ->whereRaw('LOWER(TRIM(kegiatan)) = ?', [strtolower($trimmedKegiatan)])
            ->exists();

        if ($sudahAda) {
            $formattedTanggal = Carbon::parse($tanggalInput)->locale('id')->translatedFormat('l, d F Y');
            return back()
                ->withInput()
                ->withErrors([
                    'kegiatan' => "Kegiatan ini sudah pernah di-upload sebelumnya pada {$formattedTanggal}. Kegiatan yang sama tidak dapat di-upload kembali."
                ]);
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('kegiatan', 'public');
        }

        Kegiatan::create($validated);

        return redirect()->route('kegiatan.index')
            ->with('success', 'Data kegiatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan)
    {
        return view('kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'tanggal.required' => 'Tanggal kegiatan wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'kegiatan.required' => 'Deskripsi kegiatan wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Pencegahan Duplikasi saat Update
        $trimmedKegiatan = trim($validated['kegiatan']);
        $tanggalInput = $validated['tanggal'];

        $sudahAda = Kegiatan::whereDate('tanggal', $tanggalInput)
            ->whereRaw('LOWER(TRIM(kegiatan)) = ?', [strtolower($trimmedKegiatan)])
            ->where('id', '!=', $kegiatan->id)
            ->exists();

        if ($sudahAda) {
            $formattedTanggal = Carbon::parse($tanggalInput)->locale('id')->translatedFormat('l, d F Y');
            return back()
                ->withInput()
                ->withErrors([
                    'kegiatan' => "Kegiatan ini sudah ada pada {$formattedTanggal}. Data kegiatan yang sama tidak dapat di-upload kembali."
                ]);
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($kegiatan->foto && Storage::disk('public')->exists($kegiatan->foto)) {
                Storage::disk('public')->delete($kegiatan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('kegiatan', 'public');
        }

        $kegiatan->update($validated);

        return redirect()->route('kegiatan.index')
            ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        // Hapus foto dari storage jika ada
        if ($kegiatan->foto && Storage::disk('public')->exists($kegiatan->foto)) {
            Storage::disk('public')->delete($kegiatan->foto);
        }

        $kegiatan->delete();

        return redirect()->route('kegiatan.index')
            ->with('success', 'Data kegiatan berhasil dihapus.');
    }
}
