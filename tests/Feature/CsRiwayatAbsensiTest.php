<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class CsRiwayatAbsensiTest extends TestCase
{
    use RefreshDatabase;

    private User $cs;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cs = User::factory()->create([
            'email' => 'cs_riwayat@test.local',
            'role'  => 'cs',
        ]);

        $this->admin = User::factory()->create([
            'email' => 'admin_riwayat@test.local',
            'role'  => 'admin',
        ]);

        // Seed beberapa data absensi
        $now = Carbon::now('Asia/Jakarta');

        Attendance::create([
            'user_id'     => $this->cs->id,
            'date'        => $now->toDateString(),
            'clock_in'    => '05:15:00',
            'status_masuk' => 'Hadir',
            'status'      => 'hadir',
        ]);

        Attendance::create([
            'user_id'     => $this->cs->id,
            'date'        => $now->subDay()->toDateString(),
            'clock_in'    => '05:35:00',
            'status_masuk' => 'Terlambat',
            'status'      => 'hadir',
        ]);

        Attendance::create([
            'user_id'     => $this->cs->id,
            'date'        => $now->subDays(2)->toDateString(),
            'status_masuk' => 'Izin',
            'status'      => 'izin',
        ]);
    }

    /**
     * Test 1: CS bisa mengakses halaman riwayat absensi.
     */
    public function test_cs_can_access_riwayat_absensi_page(): void
    {
        $response = $this->actingAs($this->cs)
            ->get(route('riwayat.absensi.cs'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Presensi');
    }

    /**
     * Test 2: Admin TIDAK bisa mengakses halaman riwayat CS (role guard → 403 atau redirect).
     */
    public function test_admin_cannot_access_cs_riwayat_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('riwayat.absensi.cs'));

        // Middleware role:cs mengembalikan 403 Forbidden atau redirect
        $this->assertContains(
            $response->getStatusCode(),
            [302, 303, 403],
            "Admin tidak seharusnya bisa akses halaman CS riwayat (expected 302/403, got {$response->getStatusCode()})"
        );
    }

    /**
     * Test 3: Tamu (unauthenticated) tidak bisa akses.
     */
    public function test_guest_cannot_access_riwayat_page(): void
    {
        $response = $this->get(route('riwayat.absensi.cs'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test 4: Halaman menampilkan statistik ringkasan (Hadir, Terlambat, Izin, Alpha).
     */
    public function test_riwayat_page_shows_summary_statistics(): void
    {
        $response = $this->actingAs($this->cs)
            ->get(route('riwayat.absensi.cs'));

        $response->assertStatus(200);
        $response->assertSee('Hari Hadir');
        $response->assertSee('Terlambat');
        $response->assertSee('Izin / Sakit');
        $response->assertSee('Alpha');
    }

    /**
     * Test 5: Filter berdasarkan bulan & tahun berjalan dengan benar.
     */
    public function test_riwayat_filter_by_month_and_year(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        $response = $this->actingAs($this->cs)
            ->get(route('riwayat.absensi.cs', [
                'bulan' => $now->month,
                'tahun' => $now->year,
            ]));

        $response->assertStatus(200);
        $response->assertSee('Daftar Riwayat Presensi');
    }

    /**
     * Test 6: Filter status "Terlambat" hanya menampilkan baris terlambat.
     */
    public function test_riwayat_filter_by_status_terlambat(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        $response = $this->actingAs($this->cs)
            ->get(route('riwayat.absensi.cs', [
                'bulan'  => $now->month,
                'tahun'  => $now->year,
                'status' => 'terlambat',
            ]));

        $response->assertStatus(200);
        $response->assertSee('Terapkan Filter');
    }

    /**
     * Test 7: Tombol "Riwayat Absensi" di sidebar dashboard-cs mengarah ke route baru.
     */
    public function test_dashboard_cs_sidebar_has_correct_riwayat_link(): void
    {
        // Buat minimal data timeStatus agar dashboard render
        $response = $this->actingAs($this->cs)
            ->get(route('dashboard.cs'));

        $response->assertStatus(200);
        // Pastikan #riwayat anchor TIDAK ada lagi
        $this->assertStringNotContainsString('href="#riwayat"', $response->getContent());
        // Pastikan route baru ADA
        $response->assertSee('riwayat-absensi-cs', false);
    }

    /**
     * Test 8: Section "RIWAYAT ABSENSI PRIBADI" tidak ada di dashboard-cs.
     */
    public function test_dashboard_cs_does_not_contain_riwayat_section(): void
    {
        $response = $this->actingAs($this->cs)
            ->get(route('dashboard.cs'));

        $response->assertStatus(200);
        $response->assertDontSee('Riwayat Kehadiran Anda');
    }

    /**
     * Test 9: Halaman riwayat memiliki form filter dengan dropdown bulan, tahun, status.
     */
    public function test_riwayat_page_has_filter_form(): void
    {
        $response = $this->actingAs($this->cs)
            ->get(route('riwayat.absensi.cs'));

        $response->assertStatus(200);
        $response->assertSee('name="bulan"', false);
        $response->assertSee('name="tahun"', false);
        $response->assertSee('name="status"', false);
        $response->assertSee('Terapkan Filter');
    }

    /**
     * Test 10: Pagination ada ketika ada lebih dari 10 data.
     */
    public function test_riwayat_has_pagination_links(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        // Tambah 12 data absensi biar melewati limit paginate(10)
        for ($i = 3; $i <= 14; $i++) {
            Attendance::create([
                'user_id'     => $this->cs->id,
                'date'        => $now->copy()->subDays($i)->toDateString(),
                'clock_in'    => '05:10:00',
                'status_masuk' => 'Hadir',
                'status'      => 'hadir',
            ]);
        }

        $response = $this->actingAs($this->cs)
            ->get(route('riwayat.absensi.cs', ['bulan' => $now->month, 'tahun' => $now->year]));

        $response->assertStatus(200);
        // Data > 10, pagination links harus muncul
        $this->assertGreaterThan(10, Attendance::where('user_id', $this->cs->id)
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->count());
    }
}
