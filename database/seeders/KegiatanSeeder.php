<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum seeding
        Kegiatan::truncate();

        $data = [
            // 1 September 2026
            [
                'tanggal' => '2026-09-01',
                'kegiatan' => 'Mewakili Bapak Kalaksa hadir pada acara peresmian Gedung Mudakara, Gedung Mandalawangi, Gedung Arogya Ghati RSJ Grhasia.',
                'foto' => 'kegiatan/peresmian_gedung.jpg',
            ],
            [
                'tanggal' => '2026-09-01',
                'kegiatan' => 'FGD Pemantauan dan Evaluasi Adaptasi Perubahan Iklim di Yogyakarta di Hotel Harper Malioboro.',
                'foto' => 'kegiatan/fgd_perubahan_iklim.jpg',
            ],

            // 2 September 2026
            [
                'tanggal' => '2026-09-02',
                'kegiatan' => 'Pembentukan Pemadam Kebakaran.',
                'foto' => 'kegiatan/pembentukan_damkar.jpg',
            ],
            [
                'tanggal' => '2026-09-02',
                'kegiatan' => 'Distribusi air bersih ke Padukuhan Gandu, Semugih, Rongkop, Gunungkidul.',
                'foto' => 'kegiatan/distribusi_air_bersih.jpg',
            ],
            [
                'tanggal' => '2026-09-02',
                'kegiatan' => 'Diklat Redkar Kalurahan Nglanggeran Kapanewon Patuk Gunungkidul, bekerja sama dengan UPT Damkarmat BPBD Kab. Gunungkidul.',
                'foto' => 'kegiatan/diklat_redkar.jpg',
            ],

            // 3 September 2026
            [
                'tanggal' => '2026-09-03',
                'kegiatan' => 'Rapat Koordinasi Kesiapsiagaan Menghadapi Potensi Bencana Hidrometeorologi Basah bersama BMKG DIY dan BPBD Kabupaten/Kota.',
                'foto' => 'kegiatan/rakor_hidrometeorologi.jpg',
            ],
            [
                'tanggal' => '2026-09-03',
                'kegiatan' => 'Monitoring Early Warning System (EWS) Tanah Longsor di Kawasan Perbukitan Menoreh, Kulon Progo.',
                'foto' => null,
            ],

            // 4 September 2026
            [
                'tanggal' => '2026-09-04',
                'kegiatan' => 'Patroli Siaga Gunung Merapi dan Koordinasi Pos Pengamatan Selo dan Kaliurang Sleman.',
                'foto' => 'kegiatan/patroli_merapi.jpg',
            ],
            [
                'tanggal' => '2026-09-04',
                'kegiatan' => 'Pendampingan Pembentukan Satuan Pendidikan Aman Bencana (SPAB) di SMA Negeri 1 Cangkringan.',
                'foto' => 'kegiatan/sosialisasi_spab.jpg',
            ],
            [
                'tanggal' => '2026-09-04',
                'kegiatan' => 'Pembersihan material pohon tumbang di ruas jalan Palagan Tentara Pelajar km 9 oleh Tim Reaksi Cepat (TRC) BPBD DIY.',
                'foto' => null,
            ],

            // 5 September 2026
            [
                'tanggal' => '2026-09-05',
                'kegiatan' => 'Sosialisasi Mitigasi Gempa Bumi dan Tsunami bagi Pelaku Usaha Wisata Pantai Parangtritis Bantul.',
                'foto' => 'kegiatan/fgd_perubahan_iklim.jpg',
            ],
            [
                'tanggal' => '2026-09-05',
                'kegiatan' => 'Penyaluran Bantuan Logistik bagi Korban Dampak Angin Kencang di Kapanewon Semanu Gunungkidul.',
                'foto' => 'kegiatan/distribusi_air_bersih.jpg',
            ],

            // 7 September 2026
            [
                'tanggal' => '2026-09-07',
                'kegiatan' => 'Apel Siaga Bencana dan Pengecekan Kesiapan Kendaraan Operasional Penanggulangan Bencana BPBD DIY.',
                'foto' => 'kegiatan/pembentukan_damkar.jpg',
            ],
            [
                'tanggal' => '2026-09-07',
                'kegiatan' => 'Penyusunan Rencana Kontinjensi Erupsi Gunungapi Merapi bersama FPRB DIY.',
                'foto' => null,
            ],

            // 8 September 2026
            [
                'tanggal' => '2026-09-08',
                'kegiatan' => 'Pelatihan Water Rescue bagi Personel TRC dan Relawan Bencana di Waduk Sermo Kulon Progo.',
                'foto' => 'kegiatan/diklat_redkar.jpg',
            ],
            [
                'tanggal' => '2026-09-08',
                'kegiatan' => 'Pemberian Pelayanan Pendataan Pengungsi Berbasis Digital (InaRISK) di Kabupaten Bantul.',
                'foto' => null,
            ],

            // 9 September 2026
            [
                'tanggal' => '2026-09-09',
                'kegiatan' => 'Gladi Lapang Simulasi Evakuasi Mandiri Bencana Gempa Megathrust di Pesisir Selatan Glagah Kulon Progo.',
                'foto' => 'kegiatan/rakor_hidrometeorologi.jpg',
            ],
            [
                'tanggal' => '2026-09-09',
                'kegiatan' => 'Penerimaan Kunjungan Studi Komparasi BPBD Provinsi Jawa Tengah mengenai Pengelolaan Posko PB DIY.',
                'foto' => 'kegiatan/peresmian_gedung.jpg',
            ],
        ];

        foreach ($data as $item) {
            Kegiatan::create($item);
        }
    }
}
