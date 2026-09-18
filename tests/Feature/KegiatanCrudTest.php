<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KegiatanCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_kegiatan_index_page_loads_successfully(): void
    {
        $response = $this->get(route('kegiatan.index'));

        $response->assertStatus(200);
        $response->assertSee('KEGIATAN BPBD DIY');
        $response->assertSee('HARI/TANGGAL');
    }

    public function test_kegiatan_can_be_created_with_photo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('kegiatan_uji.jpg', 150, 'image/jpeg');

        $response = $this->post(route('kegiatan.store'), [
            'tanggal' => '2026-09-10',
            'kegiatan' => 'Kegiatan Uji Coba Penanganan Bencana',
            'foto' => $file,
        ]);

        $response->assertRedirect(route('kegiatan.index'));
        $response->assertSessionHas('success', 'Data kegiatan berhasil ditambahkan.');

        $kegiatan = Kegiatan::where('kegiatan', 'Kegiatan Uji Coba Penanganan Bencana')->first();
        $this->assertNotNull($kegiatan);
        $this->assertEquals('2026-09-10', $kegiatan->tanggal->format('Y-m-d'));
        $this->assertNotNull($kegiatan->foto);
        Storage::disk('public')->assertExists($kegiatan->foto);
    }

    public function test_kegiatan_validation_requires_tanggal_and_kegiatan(): void
    {
        $response = $this->post(route('kegiatan.store'), [
            'tanggal' => '',
            'kegiatan' => '',
        ]);

        $response->assertSessionHasErrors(['tanggal', 'kegiatan']);
    }

    public function test_kegiatan_can_be_updated(): void
    {
        $kegiatan = Kegiatan::create([
            'tanggal' => '2026-09-11',
            'kegiatan' => 'Kegiatan Sebelum Diupdate',
            'foto' => null,
        ]);

        $response = $this->put(route('kegiatan.update', $kegiatan->id), [
            'tanggal' => '2026-09-12',
            'kegiatan' => 'Kegiatan Setelah Diupdate',
        ]);

        $response->assertRedirect(route('kegiatan.index'));
        $response->assertSessionHas('success', 'Data kegiatan berhasil diperbarui.');

        $this->assertEquals('2026-09-12', $kegiatan->fresh()->tanggal->format('Y-m-d'));
        $this->assertEquals('Kegiatan Setelah Diupdate', $kegiatan->fresh()->kegiatan);
    }

    public function test_kegiatan_can_be_deleted(): void
    {
        $kegiatan = Kegiatan::create([
            'tanggal' => '2026-09-15',
            'kegiatan' => 'Kegiatan Yang Akan Dihapus',
            'foto' => null,
        ]);

        $response = $this->delete(route('kegiatan.destroy', $kegiatan->id));

        $response->assertRedirect(route('kegiatan.index'));
        $response->assertSessionHas('success', 'Data kegiatan berhasil dihapus.');

        $this->assertDatabaseMissing('kegiatans', [
            'id' => $kegiatan->id,
        ]);
    }

    public function test_search_and_filter_work(): void
    {
        Kegiatan::create([
            'tanggal' => '2026-09-02',
            'kegiatan' => 'Pembentukan Pemadam Kebakaran.',
            'foto' => null,
        ]);

        $responseSearch = $this->get(route('kegiatan.index', ['search' => 'Pemadam']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Pembentukan Pemadam Kebakaran');

        $responseFilter = $this->get(route('kegiatan.index', ['bulan' => 9, 'tahun' => 2026]));
        $responseFilter->assertStatus(200);
        $responseFilter->assertSee('September 2026');
    }

    public function test_periode_tampilan_displays_semua_kegiatan_and_filtered_period(): void
    {
        Kegiatan::create([
            'tanggal' => '2026-09-02',
            'kegiatan' => 'Kegiatan Khusus Bulan September',
            'foto' => null,
        ]);

        // Saat semua kegiatan ditampilkan (tanpa filter)
        $responseAll = $this->get(route('kegiatan.index'));
        $responseAll->assertStatus(200);
        $responseAll->assertSee('Semua Kegiatan');

        // Saat bulan dan tahun dipilih
        $responseFiltered = $this->get(route('kegiatan.index', ['bulan' => 9, 'tahun' => 2026]));
        $responseFiltered->assertStatus(200);
        $responseFiltered->assertSee('September 2026');
    }

    public function test_kegiatan_cannot_be_uploaded_twice(): void
    {
        Kegiatan::create([
            'tanggal' => '2026-09-02',
            'kegiatan' => 'Pembentukan Pemadam Kebakaran.',
            'foto' => null,
        ]);

        // Percobaan upload kegiatan yang sama pada tanggal yang sama
        $responseDuplicate = $this->from(route('kegiatan.create'))->post(route('kegiatan.store'), [
            'tanggal' => '2026-09-02',
            'kegiatan' => 'Pembentukan Pemadam Kebakaran.',
            'foto' => null,
        ]);

        // Harus gagal dan menampilkan error validasi duplikasi
        $responseDuplicate->assertStatus(302);
        $responseDuplicate->assertSessionHasErrors(['kegiatan']);

        // Data tidak bertambah menjadi 2
        $this->assertEquals(1, Kegiatan::whereDate('tanggal', '2026-09-02')->where('kegiatan', 'Pembentukan Pemadam Kebakaran.')->count());
    }
}
